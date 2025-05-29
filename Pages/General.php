<?
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'administrator') {
    header("Location: ../index.php");
    exit;
}
$userLogin = $_SESSION['user']['login'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/pages.css">
    <script src="https://code.jquery.com/jquery-3.6.3.js"></script>
    
    <title>Аудитория</title>
</head>
<body>
<header>
    <div class="nav">
        <a href="Classroom.php" style="color: #dc3545; font-weight: bold;">Аудитория</a>
        <a href="Equipment.php">Оборудование</a>
        <a href="Inventory.php">Инвентаризация</a>
        <a href="EquipmentMovie.php">Перемещение оборудования</a>
        <a href="InventoryResults.php">Результаты инвентаризации</a>
        <a href="NetworkSettings.php">Настройки сети</a>
        <a href="Users.php">Пользователи</a>
        <a href="#" style="color: #dc3545; font-weight: bold;">Общее</a>
    </div>
    <div class="user">
        <p><?= htmlspecialchars($userLogin) ?></p>
        <a href="../logout.php">Выйти</a>
    </div>
</header>
<main>
    <a href="Status.php">Статусы</a>
    <a href="Direction.php">Направление</a>
    <a href="Programs.php">Программы</a>
    <a href="import.php">Импорт оборудования (.xls)</a>
</main>
</body>
</html>
