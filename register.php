<?php
session_start();
require_once 'database.php';

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $password = $_POST['password'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    
    // Валидация
    if (!preg_match('/^[а-яА-ЯёЁa-zA-Z0-9]{6,}$/u', $login)) {
        $error = "Логин: кириллица или латиница, минимум 6 символов";
    } elseif (strlen($password) < 6) {
        $error = "Пароль минимум 6 символов";
    } elseif (!preg_match('/^\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}$/', $phone)) {
        $error = "Телефон в формате +7(XXX)-XXX-XX-XX";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Некорректный email";
    } else {
        if ($db->createUser($login, $password, $first_name, $last_name, $phone, $email)) {
            $success = "Регистрация успешна! Теперь войдите.";
        } else {
            $error = "Логин уже существует";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Регистрация</h2>
    <?php if($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
    <?php if($success): ?><div class="success"><?= $success ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group"><label>Логин (кириллица/латиница, ≥6)</label><input name="login" required></div>
        <div class="form-group"><label>Пароль (≥6)</label><input type="password" name="password" required></div>
        <div class="form-group"><label>Имя</label><input name="first_name" required></div>
        <div class="form-group"><label>Фамилия</label><input name="last_name" required></div>
        <div class="form-group"><label>Телефон (+7(XXX)-XXX-XX-XX)</label><input name="phone" placeholder="+7(999)-123-45-67" required></div>
        <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
        <button type="submit">Зарегистрироваться</button>
    </form>
    <p style="text-align:center; margin-top:16px"><a href="login.php">Уже есть аккаунт? Войти</a></p>
</div>
</body>
</html>