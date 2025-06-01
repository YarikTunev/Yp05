
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.3.js"></script>
    <link rel="stylesheet" href="../styles/pages.css">
    <title>Пользователи</title>
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
        <a href="Users.php" style="color: #dc3545; font-weight: bold;">Пользователи</a>
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
            <button class="btn btn-add" id="openAddModal">Добавить запись</button>
            <button class="btn btn-delete">Удалить</button>
        </div>
    </div>
<div id="addUserModal" class="modal">
<div class="modal-content">
    <span class="close">&times;</span>
    <h2>Добавить пользователя</h2>
    <form id="addForm" >
        <label for="login">Логин:</label>
        <input type="text" id="login" name="login" required>

        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>

        <label for="role">Роль:</label>
        
        <select id="role" name="role" required>
            <option value="administrator">administrator</option>
            <option value="teacher">teacher</option>
            <option value="staff">staff</option>
        </select>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" >

        <label for="last_name">Фамилия:</label>
        <input type="text" id="last_name" name="last_name" required>

        <label for="first_name">Имя:</label>
        <input type="text" id="first_name" name="first_name" >

        <label for="middle_name">Отчество:</label>
        <input type="text" id="middle_name" name="middle_name" >

        <label for="phone">Телефон:</label>
        <input type="text" id="phone" name="phone" >

        <label for="address">Адрес:</label>
        <input type="text" id="address" name="address" >

        <button type="submit" class="btn btn-add">Добавить</button>
    </form>
</div>
</div>
<div id="editUserModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Редактировать пользователя</h2>
        <form id="editForm">
            <input type="hidden" id="editId" name="id">
            <label for="editLogin">Логин:</label>
            <input type="text" id="editLogin" name="login" required>

            <label for="editPassword">Пароль:</label>
            <input type="password" id="editPassword" name="password" required>

            <label for="editRole">Роль:</label>
            <select id="editRole" name="role" required>
                <option value="">Выберите роль</option>
                <option value="administrator">administrator</option>
                <option value="teacher">teacher</option>
                <option value="staff">staff</option>
            </select>

            <label for="editEmail">Email:</label>
            <input type="email" id="editEmail" name="email">

            <label for="editLastName">Фамилия:</label>
            <input type="text" id="editLastName" name="last_name" required>

            <label for="editFirstName">Имя:</label>
            <input type="text" id="editFirstName" name="first_name">

            <label for="editMiddleName">Отчество:</label>
            <input type="text" id="editMiddleName" name="middle_name">

            <label for="editPhone">Телефон:</label>
            <input type="text" id="editPhone" name="phone">

            <label for="editAddress">Адрес:</label>
            <input type="text" id="editAddress" name="address">

            <button type="submit" class="btn btn-update">Обновить</button>
        </form>
    </div>
</div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Логин</th>
                    <th>Пароль</th>
                    <th>Роль</th>
                    <th>Почта</th>
                    <th>Фамилия</th>
                    <th>Имя</th>
                    <th>Отчество</th>
                    <th>Номер телефона</th>
                    <th>Адрес</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
           
            </tbody>
        </table>
    </div>
</main>
</body>
<script src="../backend/js/Users.js"></script>
</html>

