<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $guests = (int)$_POST['guests'];
    $phone = $_POST['phone'];
    
    if ($guests < 1 || $guests > 10) {
        $error = "Гостей от 1 до 10";
    } elseif (!preg_match('/^\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}$/', $phone)) {
        $error = "Телефон в формате +7(XXX)-XXX-XX-XX";
    } else {
        $db->createBooking($_SESSION['user_id'], $date, $time, $guests, $phone);
        $success = "Заявка отправлена администратору!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Бронирование</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Забронировать</h2>
    <?php if($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
    <?php if($success): ?><div class="success"><?= $success ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group"><label>Дата</label><input type="date" name="date" required></div>
        <div class="form-group"><label>Время (ЧЧ:ММ)</label><input type="time" name="time" required></div>
        <div class="form-group"><label>Количество гостей (1-10)</label><input type="number" name="guests" min="1" max="10" required></div>
        <div class="form-group"><label>Контактный телефон</label><input name="phone" placeholder="+7(999)-123-45-67" required></div>
        <button type="submit">Забронировать</button>
    </form>
    <div class="nav-links" style="margin-top:20px"><a href="dashboard.php">Назад к бронированиям</a></div>
</div>
</body>
</html>