<?php
session_start();
require_once "connection.php";
$db = Connection::connect();

// Обработка входа
if (isset($_POST["login"])) {
    $login = mysqli_real_escape_string($db, $_POST["login"]);
    $password = mysqli_real_escape_string($db, $_POST["password"]);

    if (empty($login) || empty($password)) {
        $_SESSION['error'] = "Все поля обязательны для заполнения";
    } else {
        $query = "SELECT * FROM Users WHERE login = '$login' AND password = '$password'";
        $result = mysqli_query($db, $query);
        
        if ($result && mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            
            $_SESSION['user'] = [
                'id_users' => $user['id_users'],
                'login' => $user['login'],
                'email' => $user['email'],
                'role' => $user['role']
            ];
            
            header("Location: " . ($user['role'] == 'admin' ? 'admin.php' : 'client.php'));
            exit();
        } else {
            $_SESSION['error'] = "Неверный email или пароль";
        }
    }
}

// Обработка выхода
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: auth.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/auth.css">
    <title>Авторизация</title>
</head>
<body>
    <div class="login-form">
        <div class="up">
            <img src="img/auth.png" alt="">
            <h2>Авторизация</h2>
        </div>
            <?php if (isset($_SESSION['error']) && isset($_POST['login'])): ?>
                <div class="error"><?= $_SESSION['error'] ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="success"><?= $_SESSION['success'] ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            <form action="" method="post">
                <div class="form-group">
                    <label for="login">Логин</label>
                    <input type="text" id="login" name="login" required>
                </div>
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" name="login" class="submit-btn">Войти</button>
            </form>
        </div>
    </div>

    <script>
        function showRegister() {
            document.querySelectorAll('.auth-form-container')[0].style.display = 'none';
            document.getElementById('register-form').style.display = 'block';
        }
        
        function showLogin() {
            document.querySelectorAll('.auth-form-container')[0].style.display = 'block';
            document.getElementById('register-form').style.display = 'none';
        }

        <?php if (isset($_POST['register'])): ?>
            showRegister();
        <?php endif; ?>
    </script>
</body>
</html>