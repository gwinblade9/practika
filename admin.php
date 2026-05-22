<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['login'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Смена статуса
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $db->updateBookingStatus($_POST['booking_id'], $_POST['status']);
    header('Location: admin.php');
    exit;
}

$filter = $_GET['status'] ?? 'all';
$allBookings = $db->getAllBookingsWithUsers();

if ($filter !== 'all') {
    $filtered = [];
    foreach ($allBookings as $b) {
        if ($b['status'] === $filter) {
            $filtered[] = $b;
        }
    }
    $bookings = $filtered;
} else {
    $bookings = $allBookings;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ панель</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container admin">
    <h2>Администратор</h2>
    <div class="nav-links">
        <a href="index.php">Главная</a>
        <a href="dashboard.php">Мои брони</a>
        <a href="logout.php">Выйти</a>
    </div>
    
    <div style="margin: 20px 0">
        <label>Фильтр по статусу: </label>
        <select onchange="location.href='?status='+this.value">
            <option value="all" <?= $filter=='all'?'selected':'' ?>>Все</option>
            <option value="Новое" <?= $filter=='Новое'?'selected':'' ?>>Новое</option>
            <option value="Посещение состоялось" <?= $filter=='Посещение состоялось'?'selected':'' ?>>Посещение состоялось</option>
            <option value="Отменено" <?= $filter=='Отменено'?'selected':'' ?>>Отменено</option>
        </select>
    </div>
    
    <div style="overflow-x: auto">
        <table>
            <thead>
                <tr><th>ID</th><th>Пользователь</th><th>Дата/Время</th><th>Гостей</th><th>Телефон</th><th>Статус</th><th>Действие</th></tr>
            </thead>
            <tbody>
            <?php foreach($bookings as $row): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['user_name']) ?></td>
                <td><?= $row['booking_date'] ?> <?= $row['booking_time'] ?></td>
                <td><?= $row['guests'] ?></td>
                <td><?= $row['phone'] ?></td>
                <td><strong><?= $row['status'] ?></strong></td>
                <td>
                    <form method="POST" style="display:flex; gap:4px;">
                        <input type="hidden" name="booking_id" value="<?= $row['id'] ?>">
                        <select name="status">
                            <option value="Новое" <?= $row['status']=='Новое'?'selected':'' ?>>Новое</option>
                            <option value="Посещение состоялось" <?= $row['status']=='Посещение состоялось'?'selected':'' ?>>Посещение состоялось</option>
                            <option value="Отменено" <?= $row['status']=='Отменено'?'selected':'' ?>>Отменено</option>
                        </select>
                        <button type="submit">Изменить</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>