<?php
session_start();
require_once 'database.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];
    
    $user = $db->getUserByLogin($login);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['login'] = $user['login'];
        header('Location: index.php');
        exit;
    } else {
        $error = "Неверный логин или пароль";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Авторизация</h2>
    <?php if($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group"><label>Логин</label><input name="login" required></div>
        <div class="form-group"><label>Пароль</label><input type="password" name="password" required></div>
        <button type="submit">Войти</button>
    </form>
    <p style="text-align:center; margin-top:16px"><a href="register.php">Нет аккаунта? Зарегистрироваться</a></p>
</div>
</body>
</html>