<?php
session_start();
require_once "backend/classes/Users.php";
require_once "connection.php";
$db = Connection::connect();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($login) || empty($password)) {
        $error = "Введите логин и пароль.";
    } else {
        $user = Users::GetByLogin($login);

        if ($user && $password) {
            $_SESSION['user'] = [
                'id' => $user->id,
                'login' => $user->login,
                'role' => $user->role
            ];

            if ($user->role === 'administrator') {
                header("Location: Pages/Users.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Неверный логин или пароль.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Авторизация</title>
    <link rel="stylesheet" href="styles/auth.css">
</head>
<body>
<div class="login-form">
    <h2>Войти</h2>
    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" action="">
        <input type="text" name="login" placeholder="Логин" required><br>
        <input type="password" name="password" placeholder="Пароль" required><br>
        <button type="submit">Войти</button>
    </form>
</div>
</body>
</html>