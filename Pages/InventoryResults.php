<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.3.js"></script>
    <link rel="stylesheet" href="../styles/pages.css">
    <title>Результат инвентаризации</title>
</head>
<body>
<header>
    <div class="nav">
        <a href="Classroom.php">Аудитория</a>
        <a href="Equipment.php">Оборудование</a>
        <a href="Inventory.php">Инвентаризация</a>
        <a href="EquipmentMovie.php">Перемещение оборудования</a>
        <a href="#" style="color: #dc3545; font-weight: bold;">Результаты инвентаризации</a>
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
    <div id="addInventoryResultsModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Добавить результат инвентаризации</h2>
            <form id="addForm">
                <label for="inventory_id">Номер инвентаризации:</label>
                <input type="text" id="session_id" name="session_id" required>

                <label for="equipment_id">Номер оборудования:</label>
                <input type="text" id="equipment_id" name="equipment_id" required>

                <label for="user_id">Пользователь:</label>
                <input type="text" id="user_id" name="user_id" required>

                <label for="checked_by">Номер проверяющего:</label>
                <input type="text" id="checked_by" name="checked_by" required>

                <label for="check_date">Дата проверки:</label>
                <input type="datetime-local" id="check_date" name="check_date" required>

                <label for="status_id">Статус:</label>
                <select id="status_id" name="status_id" required>
                    <option value="Используется">Используется</option>
                    <option value="На ремонте">На ремонте</option>
                </select>

                <label for="comment">Комментарий:</label>
                <input type="text" id="comment" name="comment">

                <button type="submit" class="btn btn-add">Добавить</button>
            </form>
        </div>
    </div>
    <div id="editInventoryResultsModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Редактировать результат инвентаризации</h2>
            <form id="editForm">
                <input type="hidden" id="editId" name="id">
                <label for="editSessionId">Номер инвентаризации:</label>
                <input type="text" id="editSessionId" name="session_id" required>

                <label for="editEquipmentId">Номер оборудования:</label>
                <input type="text" id="editEquipmentId" name="equipment_id" required>

                <label for="editUserId">Пользователь:</label>
                <input type="text" id="editUser_id" name="user_id" required>

                <label for="editCheckedBy">Номер проверяющего:</label>
                <input type="text" id="editCheckedBy" name="checked_by" required>

                <label for="editCheckDate">Дата проверки:</label>
                <input type="datetime" id="editCheckDate" name="check_date" required>

                <label for="editStatus">Статус:</label>
                <select id="editStatus" name="status" required>
                    <option value="Используется">Используется</option>
                    <option value="На ремонте">На ремонте</option>
                </select>

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
                    <th>Номер инвентаризации</th>
                    <th>Номер оборудования</th>
                    <th>Пользователь</th>
                    <th>Номер проверяющего</th>
                    <th>Дата проверки</th>
                    <th>Статус</th>
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
<script src="../backend/js/InventoryResults.js"></script>
</html>
