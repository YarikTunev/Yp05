<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.3.js"></script>
    <link rel="stylesheet" href="../styles/pages.css">
    <title>Расходники</title>
</head>
<body>
<header>
    <div class="nav">
        <a href="General.php">Общее</a>
        <a href="Classroom.php">Аудитория</a>
        <a href="Equipment.php">Оборудование</a>
        <a href="Inventory.php">Инвентаризация</a>
        <a href="EquipmentMovie.php">Перемещение оборудования</a>
        <a href="InventoryResults.php">Результаты инвентаризации</a>
        <a href="NetworkSettings.php">Настройки сети</a>
        <a href="Users.php">Пользователи</a>
        <a href="Models.php">Модели</a>
        <a href="Consumables.php" style="color: #dc3545; font-weight: bold;">Расходники</a>
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

    <div id="addConsumableModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Добавить расходник</h2>
            <form id="addForm" enctype="multipart/form-data">
                <label for="name">Название:</label>
                <input type="text" id="name" name="name" required>

                <label for="description">Описание:</label>
                <textarea id="description" name="description"></textarea>

                <label for="date_received">Дата получения:</label>
                <input type="date" id="date_received" name="date_received" required>

                <label for="image">Изображение:</label>
                <input type="file" id="image" name="image" accept="image/*">

                <label for="quantity">Количество:</label>
                <input type="number" id="quantity" name="quantity" required>

                <label for="cost">Цена:</label>
                <input type="number" id="cost" name="cost" required>

                <label for="responsible_user_id">ID ответственного:</label>
                <input type="number" id="responsible_user_id" name="responsible_user_id">

                <label for="temp_responsible_user_id">ID временного ответственного:</label>
                <input type="number" id="temp_responsible_user_id" name="temp_responsible_user_id">

                <label for="consumable_type_id">ID типа расходника:</label>
                <input type="number" id="consumable_type_id" name="consumable_type_id">

                <button type="submit" class="btn btn-add">Добавить</button>
            </form>
        </div>
    </div>

    <div id="editConsumableModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Редактировать расходник</h2>
            <form id="editForm" enctype="multipart/form-data">
                <input type="hidden" id="editId" name="id">
                <label for="editName">Название:</label>
                <input type="text" id="editName" name="name" required>

                <label for="editDescription">Описание:</label>
                <textarea id="editDescription" name="description"></textarea>

                <label for="editDateReceived">Дата получения:</label>
                <input type="date" id="editDateReceived" name="date_received" required>

                <label for="editImage">Изображение:</label>
                <input type="file" id="editImage" name="image" accept="image/*">

                <label for="editQuantity">Количество:</label>
                <input type="number" id="editQuantity" name="quantity" required>

                <label for="editCost">Цена:</label>
                <input type="number" id="editCost" name="cost" required>

                <label for="editResponsibleUserId">ID ответственного:</label>
                <input type="number" id="editResponsibleUserId" name="responsible_user_id">

                <label for="editTempResponsibleUserId">ID временного ответственного:</label>
                <input type="number" id="editTempResponsibleUserId" name="temp_responsible_user_id">

                <label for="editConsumableTypeId">ID типа расходника:</label>
                <input type="number" id="editConsumableTypeId" name="consumable_type_id">

                <button type="submit" class="btn btn-update">Обновить</button>
            </form>
        </div>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Описание</th>
                    <th>Дата получения</th>
                    <th>Изображение</th>
                    <th>Количество</th>
                    <th>Цена</th>
                    <th>ID ответственного</th>
                    <th>ID временного ответственного</th>
                    <th>ID типа расходника</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</main>
<script src="../backend/js/Consumables.js"></script>
</body>
</html>