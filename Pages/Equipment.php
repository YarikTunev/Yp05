
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.3.js"></script>
    <link rel="stylesheet" href="../styles/pages.css">
    <title>Оборудование</title>
</head>
<body>
<header>
    <div class="nav">
        <a href="General.php">Общее</a>
        <a href="Classroom.php">Аудитория</a>
        <a href="Equipment.php" style="color: #dc3545; font-weight: bold;">Оборудование</a>
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
    <div id="addEquipmentModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Добавить оборудование</h2>
            <form id="addForm" enctype="multipart/form-data">
                <label for="name">Наименование:</label>
                <input type="text" id="name" name="name" required>

                <label for="photo">Фотография:</label>
                <input type="file" id="photo" name="photo" accept="images/*">

                <label for="inventory_number">Инв. номер:</label>
                <input type="number" id="inventory_number" name="inventory_number" required>

                <label for="cost">Цена:</label>
                <input type="number" id="cost" name="cost">

                <label for="direction_id">Направление:</label>
                <select id="direction_id" name="direction_id" required>
                    <option value="">Выберете</option>
                    <option value="1">Ит</option>
                    <option value="2">Наука</option>
                    <option value="4">Математика</option>
                </select>

                <label for="status_id">Статус:</label>
                <select id="status" name="status_id" required>
                    <option value="">Выберете</option>
                    <option value="1">В использовании</option>
                    <option value="2">На обслуживании</option>
                    <option value="3">Списано</option>
                </select>
                
                <label for="responsible_user_id">Ответственый:</label>
                <input type="number" id="responsible_user_id" name="responsible_user_id">

                <label for="temp_responsible_user_id">Временно-ответственный:</label>
                <input type="number" id="temp_responsible_user_id" name="temp_responsible_user_id">

                <label for="model_id">Модель:</label>
                <input type="number" id="model_id" name="model_id">

                <label for="comment">Комментарий:</label>
                <input type="text" id="comment" name="comment">

                <label for="classroom_id">Номер аудитории:</label>
                <input type="number" id="classroom_id" name="classroom_id">

                <button type="submit" class="btn btn-add">Добавить</button>
            </form>
        </div>
    </div>
    <div id="editEquipmentModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Редактировать оборудование</h2>
            <form id="editForm">
                <input type="hidden" id="editId" name="id">
                <label for="editName">Наименование:</label>
                <input type="text" id="editName" name="name" required>

                <label for="editPhoto">Фотография:</label>
                <input type="file" id="editPhoto" name="photo" accept="images/*">

                <label for="editInventory_number">Инв. номер:</label>
                <input type="number" id="editInventory_number" name="inventory_number" required>

                <label for="editCost">Цена:</label>
                <input type="number" id="editCost" name="cost">

                <label for="editDirection_id">Направление:</label>
                <select id="editDirection_id" name="direction_id" required>
                    <option value="">Выберете</option>
                    <option value="1">Ит</option>
                    <option value="2">Наука</option>
                    <option value="4">Математика</option>
                </select>

                <label for="editStatus_id">Статус:</label>
                <select id="editStatus_id" name="status_id" required>
                    <option value="">Выберете</option>
                    <option value="1">В использовании</option>
                    <option value="2">На обслуживании</option>
                    <option value="3">Списано</option>
                </select>
                
                <label for="editResponsible_user_id">Ответственый:</label>
                <input type="number" id="editResponsible_user_id" name="responsible_user_id">

                <label for="editTemp_responsible_user_id">Вресенно-ответственный:</label>
                <input type="number" id="editTemp_responsible_user_id" name="temp_responsible_user_id">

                <label for="editModel_id">Модель:</label>
                <input type="number" id="editModel_id" name="model_id">

                <label for="editComment">Комментарий:</label>
                <input type="text" id="editComment" name="comment">

                <label for="editClassroom_id">Номер аудитории:</label>
                <input type="number" id="editClassroom_id" name="classroom_id">

                <button type="submit" class="btn btn-update">Обновить</button>
            </form>
        </div>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Наименование</th>
                    <th>Фотография</th>
                    <th>Инв. номер</th>
                    <th>Номер аудитории</th>
                    <th>Ответственый</th>
                    <th>Временно-ответственый</th>
                    <th>Цена</th>
                    <th>Направление</th>
                    <th>Статус</th>
                    <th>Модель</th>
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
<script src="../backend/js/Equipment.js"></script>
</html>
