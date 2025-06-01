
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
        <a href="General.php">Общее</a>
        <a href="Classroom.php" style="color: #dc3545; font-weight: bold;">Аудитория</a>
        <a href="Equipment.php">Оборудование</a>
        <a href="Inventory.php">Инвентаризация</a>
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
<div id="addClassroomModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Добавить класс</h2>
        <form id="addForm">
            <label for="name">Название класса:</label>
            <input type="text" id="name" name="name" required>

            <label for="short_name">Сокращенное название:</label>
            <input type="text" id="short_name" name="short_name">

            <label for="responsible_user_id">Ответственный:</label>
            <input type="text" id="responsible_user_id" name="responsible_user_id" required>

            <label for="temp_responsible_user_id">Временно ответственный:</label>
            <input type="text" id="temp_responsible_user_id" name="temp_responsible_user_id">

            <button type="submit" class="btn btn-add">Добавить</button>
        </form>
    </div>
</div>
<div id="editClassroomModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Редактировать класс</h2>
        <form id="editForm">
            <input type="hidden" id="editId" name="id">
            <label for="editname">Название класса:</label>
            <input type="text" id="editname" name="name" required>

            <label for="editshort_name">Сокращенное название:</label>
            <input type="text" id="editshort_name" name="short_name">

            <label for="editresponsible_user_id">Ответственный:</label>
            <input type="text" id="editresponsible_user_id" name="responsible_user_id" required>

            <label for="edittemp_responsible_user_id">Временно ответственный:</label>
            <input type="text" id="edittemp_responsible_user_id" name="temp_responsible_user_id">

            <button type="submit" class="btn btn-update">Обновить</button>
        </form>
    </div>
</div>

    <div class="table-container">
        <table id="classroomsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Наименование</th>
                    <th>Краткое наименование</th>
                    <th>Ответственный</th>
                    <th>Временно ответственный</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</main>
</body>
<script src="../backend/js/Classroom.js"></script>

</html>
