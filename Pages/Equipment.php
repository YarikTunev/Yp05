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
        <a href="Classroom.php">Аудитория</a>
        <a href="#" style="color: #dc3545; font-weight: bold;">Оборудование</a>
        <a href="Inventory.php">Инвентаризация</a>
        <a href="EquipmentMovie.php">Перемещение оборудования</a>
        <a href="InventoryResults.php">Результаты инвентаризации</a>
        <a href="NetworkSettings.php">Настройки сети</a>
        <a href="Users.php">Пользователи</a>
        <a href="General.php">Общее</a>
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

                <label for="photo_path">Фотография:</label>
                <input type="file" id="photo_path" name="photo_path" accept="images/*">

                <label for="inventory_number">Инв. номер:</label>
                <input type="text" id="inventory_number" name="inventory_number" required>

                <label for="cost">Цена:</label>
                <input type="text" id="cost" name="cost">

                <label for="direction">Направление:</label>
                <select id="direction" name="direction" required>
                    <option value="">Выберете</option>
                    <option value="Мехатроника">Мехатроника</option>
                    <option value="Общее">Общее</option>
                    <option value="Информатика">Информатика</option>
                </select>

                <label for="status">Статус:</label>
                <select id="status" name="status" required>
                    <option value="">Выберете</option>
                    <option value="На ремонте">На ремонте</option>
                    <option value="Используется">Используется</option>
                    <option value="На складе">На складе</option>
                </select>

                <label for="equipment_type">Тип обр.:</label>
                <select id="equipment_type" name="equipment_type" required>
                    <option value="">Выберете</option>
                    <option value="Ноутбук">Ноутбук</option>
                    <option value="Проектор">Проектор</option>
                    <option value="Принтер">Принтер</option>
                    <option value="Компьютер">Компьютер</option>
                    <option value="Доска">Доска</option>
                </select>

                <label for="model">Модель:</label>
                <input type="text" id="model" name="model">

                <label for="comment">Комментарий:</label>
                <input type="text" id="comment" name="comment">

                <label for="created_at">Созданно:</label>
                <input type="datetime-local" step="1" id="created_at" name="created_at">

                <label for="updated_at">Обновлено:</label>
                <input type="datetime-local" step="1" id="updated_at" name="updated_at">

                <label for="classroom_id">Номер аудитории:</label>
                <input type="text" id="classroom_id" name="classroom_id">

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

                <label for="editPhotoPath">Фотография:</label>
                <input type="text" id="editPhotoPath" name="photo_path">

                <label for="editInventoryNumber">Инв. номер:</label>
                <input type="text" id="editInventoryNumber" name="inventory_number" required>

                <label for="editCost">Цена:</label>
                <input type="text" id="editCost" name="cost">

                <label for="editDirection">Направление:</label>
                <select id="editDirection" name="direction" required>
                    <option value="1">Мехатроника</option>
                    <option value="2">Общее</option>
                    <option value="3">Информатика</option>
                </select>

                <label for="editStatus">Статус:</label>
                <select id="editStatus" name="status" required>
                    <option value="Н1">На ремонте</option>
                    <option value="2">Используется</option>
                    <option value="3">На складе</option>
                </select>

                <label for="editEquipmentType">Тип обр.:</label>
                <select id="editEquipmentType" name="equipment_type" required>
                    <option value="1">Ноутбук</option>
                    <option value="2">Проектор</option>
                    <option value="3">Принтер</option>
                    <option value="4">Компьютер</option>
                    <option value="5">Доска</option>
                </select>

                <label for="editModel">Модель:</label>
                <input type="text" id="editModel" name="model" required>

                <label for="editComment">Комментарий:</label>
                <input type="text" id="editComment" name="comment">

                <label for="editCreatedAt">Созданно:</label>
                <input type="datetime-local" step="1" id="editCreatedAt" name="created_at">

                <label for="editUpdatedAt">Обновлено:</label>
                <input type="datetime-local" step="1" id="editUpdatedAt" name="updated_at">

                <label for="editClassroomId">Номер аудитории:</label>
                <input type="text" id="editClassroomId" name="classroom_id">

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
                    <th>Цена</th>
                    <th>Направление</th>
                    <th>Статус</th>
                    <th>Тип обр.</th>
                    <th>Модель</th>
                    <th>Комментарий</th>
                    <th>Созданно</th>
                    <th>Обновлено</th>
                    <th>Номер аудитории</th>
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
