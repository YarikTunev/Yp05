
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/pages.css">
    <script src="https://code.jquery.com/jquery-3.6.3.js"></script>
    <title>Настройки сети</title>
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
        <a href="NetworkSettings.php" style="color: #dc3545; font-weight: bold;">Настройки сети</a>
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
    <div id="addNetworkSettingsModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Добавить настройки сети</h2>
            <form id="addForm">
                <label for="equipment_id">Номер оборудования:</label>
                <input type="text" id="equipment_id" name="equipment_id" required>

                <label for="ip_address">IP Адрес:</label>
                <input type="text" id="ip_address" name="ip_address" required>

                <label for="subnet_mask">Маска сети:</label>
                <input type="text" id="subnet_mask" name="subnet_mask" required>

                <label for="gateway">Gateway:</label>
                <input type="text" id="gateway" name="gateway" required>

                <label for="dns_servers">dns_servers:</label>
                <input type="text" id="dns_servers" name="dns_servers">

                <button type="submit" class="btn btn-add">Добавить</button>
            </form>
        </div>
    </div>
    <div id="editNetworkSettingsModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Редактировать настройки сети</h2>
            <form id="editForm">
                <input type="hidden" id="editId" name="id">
                <label for="editEquipmentId">Номер оборудования:</label>
                <input type="text" id="editEquipmentId" name="equipment_id" required>

                <label for="editIpAddress">IP Адрес:</label>
                <input type="text" id="editIpAddress" name="ip_address" required>

                <label for="editSubnetMask">Маска сети:</label>
                <input type="text" id="editSubnetMask" name="subnet_mask" required>

                <label for="editGateway">Gateway:</label>
                <input type="text" id="editGateway" name="gateway" required>

                <label for="editDns_servers">dns_servers:</label>
                <input type="text" id="editDns_servers" name="dns_servers">

                <button type="submit" class="btn btn-update">Обновить</button>
            </form>
        </div>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Номер оборудования</th>
                    <th>IP Адрес</th>
                    <th>Маска сети</th>
                    <th>Gateway</th>
                    <th>dns_servers</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</main>
</body>
<script src="../backend/js/NetworkSettings.js"></script>
</html>
