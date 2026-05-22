<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Я буду кушац</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Бронирование</h1>
    <p style="text-align:center; margin-bottom: 24px;">Бронирование</p>
    
    <div class="nav-links">
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="dashboard.php">Мои брони</a>
            <a href="booking.php">Забронировать</a>
            <?php if($_SESSION['login'] === 'admin'): ?>
                <a href="admin.php">Админ панель</a>
            <?php endif; ?>
            <a href="logout.php">Выйти</a>
        <?php else: ?>
            <a href="register.php">Регистрация</a>
            <a href="login.php">Вход</a>
        <?php endif; ?>
    </div>

    <?php if(!isset($_SESSION['user_id'])): ?>
        <p style="text-align:center">Добро пожаловать! Войдите или зарегистрируйтесь.</p>
    <?php else: ?>
        <p style="text-align:center">Привет, <?= htmlspecialchars($_SESSION['login']) ?>!</p>
    <?php endif; ?>
</div>
</body>
</html>