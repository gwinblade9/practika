<?php

class Database {
    private $dataDir = 'database_data/';
    
    function __construct() {
        if (!is_dir($this->dataDir)) {
            mkdir($this->dataDir, 0777, true);
        }
        // Создаём админа при первом запуске
        $this->initAdmin();
    }
    
    private function initAdmin() {
        $users = $this->getUsers();
        $adminExists = false;
        foreach ($users as $user) {
            if ($user['login'] === 'admin') {
                $adminExists = true;
                break;
            }
        }
        if (!$adminExists) {
            $users[] = [
                'id' => 1,
                'login' => 'zxc',
                'password' => password_hash('yakilka', PASSWORD_DEFAULT),
                'first_name' => 'Admin',
                'last_name' => 'Administrator',
                'phone' => '+7(000)-000-00-00',
                'email' => 'admin@.com'
            ];
            $this->saveUsers($users);
        }
    }
    
    function getUsers() {
        $file = $this->dataDir . 'users.json';
        if (!file_exists($file)) return [];
        $content = file_get_contents($file);
        return json_decode($content, true) ?: [];
    }
    
    function saveUsers($users) {
        // Убрана константа JSON_PRETTY_UNESCAPED_UNICODE для старых версий PHP
        file_put_contents($this->dataDir . 'users.json', json_encode($users));
    }
    
    function getUserByLogin($login) {
        $users = $this->getUsers();
        foreach ($users as $user) {
            if ($user['login'] === $login) {
                return $user;
            }
        }
        return null;
    }
    
    function getUserById($id) {
        $users = $this->getUsers();
        foreach ($users as $user) {
            if ($user['id'] == $id) {
                return $user;
            }
        }
        return null;
    }
    
    function createUser($login, $password, $firstName, $lastName, $phone, $email) {
        $users = $this->getUsers();
        // Проверка уникальности логина
        foreach ($users as $user) {
            if ($user['login'] === $login) {
                return false;
            }
        }
        $newId = count($users) + 1;
        $users[] = [
            'id' => $newId,
            'login' => $login,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $phone,
            'email' => $email
        ];
        $this->saveUsers($users);
        return true;
    }
    
    function getBookings($userId = null) {
        $file = $this->dataDir . 'bookings.json';
        if (!file_exists($file)) return [];
        $bookings = json_decode(file_get_contents($file), true) ?: [];
        
        if ($userId !== null) {
            $result = [];
            foreach ($bookings as $booking) {
                if ($booking['user_id'] == $userId) {
                    $result[] = $booking;
                }
            }
            return $result;
        }
        return $bookings;
    }
    
    function getAllBookingsWithUsers() {
        $bookings = $this->getBookings();
        $result = [];
        foreach ($bookings as $booking) {
            $user = $this->getUserById($booking['user_id']);
            $booking['user_login'] = $user ? $user['login'] : 'unknown';
            $booking['user_name'] = $user ? $user['first_name'] . ' ' . $user['last_name'] : 'unknown';
            $result[] = $booking;
        }
        return $result;
    }
    
    function createBooking($userId, $date, $time, $guests, $phone) {
        $bookings = $this->getBookings();
        $newId = count($bookings) + 1;
        $bookings[] = [
            'id' => $newId,
            'user_id' => $userId,
            'booking_date' => $date,
            'booking_time' => $time,
            'guests' => $guests,
            'phone' => $phone,
            'status' => 'Новое',
            'review' => '',
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->saveBookings($bookings);
        return true;
    }
    
    function saveBookings($bookings) {
        // Убрана константа JSON_PRETTY_UNESCAPED_UNICODE для старых версий PHP
        file_put_contents($this->dataDir . 'bookings.json', json_encode($bookings));
    }
    
    function updateBookingStatus($bookingId, $status) {
        $bookings = $this->getBookings();
        foreach ($bookings as &$booking) {
            if ($booking['id'] == $bookingId) {
                $booking['status'] = $status;
                break;
            }
        }
        $this->saveBookings($bookings);
    }
    
    function updateBookingReview($bookingId, $review, $userId) {
        $bookings = $this->getBookings();
        foreach ($bookings as &$booking) {
            if ($booking['id'] == $bookingId && $booking['user_id'] == $userId && $booking['status'] == 'Посещение состоялось') {
                $booking['review'] = $review;
                $this->saveBookings($bookings);
                return true;
            }
        }
        return false;
    }
    
    function query($sql) { return $this; }
    function querySingle($sql) { return null; }
    function fetchArray() { return false; }
}

// Создаём глобальный объект БД
$db = new Database();
?>