
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.3.js"></script>
    <link rel="stylesheet" href="../styles/pages.css">
    <title>Инвентаризация</title>
</head>
<body>
<header>
    <div class="nav">
        <a href="General.php">Общее</a>
        <a href="Classroom.php">Аудитория</a>
        <a href="Equipment.php">Оборудование</a>
        <a href="Inventory.php" style="color: #dc3545; font-weight: bold;">Инвентаризация</a>
        <a href="EquipmentMovie.php">Перемещение оборудования</a>
        <a href="InventoryResults.php">Результаты инвентаризации</a>
        <a href="NetworkSettings.php">Настройки сети</a>
        <a href="Users.php">Пользователи</a>
        <a href="Models.php">Модели</a>
        <a href="Consumables.php">Расходники</a>
        <a href="ConsumableTypes.php">Типы расходников</a>
        <a href="Direction.php">Направление</a>
        <a href="Programs.php">Программы</a>
        <a href="Status.php">Статус</a>
        <a href="ConsumableCharacteristics.php">Характеристики расходников</a>
    </div>
    <div class="user">
        <p>Admin</p>
        <img src="../img/down.png" alt="">
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
    <div id="addInventoryModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Добавить инвентаризацию</h2>
            <form id="addForm">
                <label for="name">Название:</label>
                <input type="text" id="name" name="name" required>

                <label for="start_date">Дата начала:</label>
                <input type="date" id="start_date" name="start_date" required>

                <label for="end_date">Дата окончания:</label>
                <input type="date" id="end_date" name="end_date" required>

                <label for="created_by">Номер проводящего:</label>
                <input type="text" id="created_by" name="created_by" required>

                <button type="submit" class="btn btn-add">Добавить</button>
            </form>
        </div>
    </div>
    <div id="editInventoryModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Редактировать инвентаризацию</h2>
            <form id="editForm">
                <input type="hidden" id="editId" name="id">
                <label for="editName">Название:</label>
                <input type="text" id="editName" name="name" required>

                <label for="editStartDate">Дата начала:</label>
                <input type="date" id="editStartDate" name="start_date" required>

                <label for="editEndDate">Дата окончания:</label>
                <input type="date" id="editEndDate" name="end_date" required>

                <label for="editCreatedBy">Номер проводящего:</label>
                <input type="text" id="editCreatedBy" name="created_by" required>

                <button type="submit" class="btn btn-update">Обновить</button>
            </form>
        </div>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Название</th>
                    <th>Дата начала</th>
                    <th>Дата окончания</th>
                    <th>Номер проводящего</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</main>
</body>
<script src="../backend/js/Inventory.js"></script>

</html>
