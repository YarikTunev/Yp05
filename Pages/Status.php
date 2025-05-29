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
    <script src="https://code.jquery.com/jquery-3.6.3.js"></script>
    <link rel="stylesheet" href="../styles/pages.css">
    <title>Статусы</title>
</head>
<body>
<header>
    <div class="nav">
        <a href="Classroom.php">Аудитория</a>
        <a href="Equipment.php">Оборудование</a>
        <a href="Inventory.php">Инвентаризация</a>
        <a href="EquipmentMovie.php">Перемещение оборудования</a>
        <a href="InventoryResults.php">Результаты инвентаризации</a>
        <a href="NetworkSettings.php">Настройки сети</a>
        <a href="Users.php">Пользователи</a>
        <a href="General.php">Общее</a>
    </div>
    <div class="user">
        <p><?= htmlspecialchars($userLogin) ?></p>
        <a href="../logout.php">Выйти</a>
    </div>
</header>
<main>
    <div class="search-container">
        <input type="text" class="search-box" placeholder="Поиск...">
        <div class="buttons">
            <button class="btn btn-import">Импортировать в SVG</button>
            <button class="btn btn-add">Добавить запись</button>
            <button class="btn btn-delete">Удалить</button>
        </div>
    </div>
<div id="addStatusModal" class="modal">
<div class="modal-content">
    <span class="close">&times;</span>
    <h2>Добавить Статус</h2>
    <form id="addForm" >
        <label for="name">статус:</label>
        <input type="text" id="name" name="name" required>

        <button type="submit" class="btn btn-add">Добавить</button>
    </form>
</div>
</div>
<div id="editStatusModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Редактировать Статус</h2>
        <form id="editForm">
            <input type="hidden" id="editid" name="id">
            <label for="editname">статус:</label>
            <input type="text" id="editname" name="name" required>
            <button type="submit" class="btn btn-update">Обновить</button>
        </form>
    </div>
</div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>статус</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
           
            </tbody>
        </table>
    </div>
</main>
</body>
<script src="../backend/js/Status.js"></script>
</html>

