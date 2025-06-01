
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.3.js"></script>
    <link rel="stylesheet" href="../styles/pages.css">
    <title>Перемещение оборудования</title>
</head>
<body>
<header>
    <div class="nav">
        <a href="General.php">Общее</a>
        <a href="Classroom.php">Аудитория</a>
        <a href="Equipment.php">Оборудование</a>
        <a href="Inventory.php">Инвентаризация</a>
        <a href="EquipmentMovie.php" style="color: #dc3545; font-weight: bold;">Перемещение оборудования</a>
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
    <div id="addEquipmentMovementHistoryModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Добавить перемещение оборудования</h2>
            <form id="addForm">
                <label for="equipment_id">ID оборудования:</label>
                <input type="text" id="equipment_id" name="equipment_id" required>

                <label for="classroom_id">ID кабинета:</label>
                <input type="text" id="classroom_id" name="classroom_id" required>

                <label for="responsible_user_id">Ответственный пользователь:</label>
                <input type="text" id="responsible_user_id" name="responsible_user_id" required>

                <label for="temp_responsible_user_id">Временный пользователь:</label>
                <input type="text" id="temp_responsible_user_id" name="temp_responsible_user_id" required>

                <label for="movement_date">Дата перемещения:</label>
                <input type="date" id="movement_date" name="movement_date" required>

                <label for="comment">Комментарий:</label>
                <input type="text" id="comment" name="comment">

                <button type="submit" class="btn btn-add">Добавить</button>
            </form>
        </div>
    </div>
    <div id="editEquipmentMovementHistoryModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Редактировать перемещение оборудования</h2>
            <form id="editForm">
                <input type="hidden" id="editId" name="id">
                <label for="editEquipmentId">ID оборудования:</label>
                <input type="text" id="editEquipmentId" name="equipment_id">

                <label for="editClassroomId">ID кабинета:</label>
                <input type="text" id="editClassroomId" name="classroom_id">

                <label for="editResponsibleUserId">Ответственный пользователь:</label>
                <input type="text" id="editResponsibleUserId" name="responsible_user_id">

                <label for="editTempResponsibleUserId">Временный пользователь:</label>
                <input type="text" id="editTempResponsibleUserId" name="temp_responsible_user_id">

                <label for="editMovementDate">Дата перемещения:</label>
                <input type="date" id="editMovementDate" name="movement_date">

                <label for="editComment">Комментарий:</label>
                <input type="text" id="editComment" name="comment">

                <button type="submit" class="btn btn-update">Обновить</button>
            </form>
        </div>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>ID оборудования</th>
                    <th>ID кабинета</th>
                    <th>Ответственный пользователь</th>
                    <th>Временный пользователь</th>
                    <th>Дата перемещения</th>
                    <th>Комментарий</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</main>
</body>
<script src="../backend/js/EquipmentMovie.js"></script>
</html>
