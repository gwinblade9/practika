<?php
$db = new SQLite3('database.sqlite');

// Таблица пользователей
$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    login TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    phone TEXT NOT NULL,
    email TEXT NOT NULL
)");

// Таблица бронирований
$db->exec("CREATE TABLE IF NOT EXISTS bookings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    booking_date TEXT NOT NULL,
    booking_time TEXT NOT NULL,
    guests INTEGER NOT NULL,
    phone TEXT NOT NULL,
    status TEXT DEFAULT 'Новое',
    review TEXT,
    visited INTEGER DEFAULT 0,
    FOREIGN KEY(user_id) REFERENCES users(id)
)");

// Добавляем админа (если нет)
$check = $db->querySingle("SELECT COUNT(*) FROM users WHERE login='zxc'");
if (!$check) {
    $hash = password_hash('yakilka', PASSWORD_DEFAULT);
    $db->exec("INSERT INTO users (login, password, first_name, last_name, phone, email) 
               VALUES ('admin', '$hash', 'Admin', 'Admin', '+7(000)-000-00-00', 'admin@rest.com')");
}

echo "База данных создана. Удалите этот файл для безопасности.";
?>