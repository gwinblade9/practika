<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Обработка отзыва
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review'])) {
    $db->updateBookingReview($_POST['booking_id'], trim($_POST['review']), $_SESSION['user_id']);
    header('Location: dashboard.php');
    exit;
}

$bookings = $db->getBookings($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои бронирования</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>📋 Мои бронирования</h2>
    <div class="nav-links">
        <a href="booking.php">+ Новое бронирование</a>
        <a href="index.php">Главная</a>
        <a href="logout.php">Выйти</a>
    </div>
    
    <?php if(empty($bookings)): ?>
        <p style="text-align:center">У вас пока нет бронирований.</p>
    <?php else: ?>
        <?php foreach($bookings as $row): ?>
        <div class="booking-card">
            <strong><?= $row['booking_date'] ?> <?= $row['booking_time'] ?></strong><br>
            Людей: <?= $row['guests'] ?> | <?= $row['phone'] ?><br>
            Статус: <strong><?= $row['status'] ?></strong>
            <?php if($row['status'] === 'Посещение состоялось' && empty($row['review'])): ?>
                <form method="POST" style="margin-top:12px">
                    <input type="hidden" name="booking_id" value="<?= $row['id'] ?>">
                    <textarea name="review" placeholder="Ваш отзыв о качестве обслуживания..." rows="2" style="width:100%"></textarea>
                    <button type="submit" style="margin-top:8px">Оставить отзыв</button>
                </form>
            <?php elseif(!empty($row['review'])): ?>
                <div class="meta">⭐ Отзыв: <?= htmlspecialchars($row['review']) ?></div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>