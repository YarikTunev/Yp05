$(document).ready(function() {
    function loadNetworkSettings() {
        let params = new FormData();
        params.append("action", "get");

        $.ajax({
            url: '../backend/controllers/NetworkSettings_add.php',
            type: 'POST',
            data: params,
            cache: false,
            processData: false,
            contentType: false,
            success: function(data) {
                let NetworkSettingss = JSON.parse(data);
                NetworkSettingss.forEach(NetworkSettings => {
                    $("tbody").append(`
                    <tr id=${NetworkSettings.id}>
                        <td>${NetworkSettings.id}</td>
                        <td>${NetworkSettings.equipment_id}</td>
                        <td>${NetworkSettings.ip_address}</td>
                        <td>${NetworkSettings.subnet_mask}</td>
                        <td>${NetworkSettings.gateway}</td>
                        <td>${NetworkSettings.dns_servers}</td>
                        <td class=action-icons>
                            <img src=../img/edit.png alt=Edit class="edit-btn" data-id="${NetworkSettings.id}">
                            <img src=../img/delete.png alt=Delete class="delete-btn" data-id="${NetworkSettings.id}">
                        </td>
                    </tr>`)
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading NetworkSettings:', error);
            }
        });
    }
    loadNetworkSettings();
});
//Добавление настроек сети
$(".btn-add").click(function () {
    $("#addNetworkSettingsModal").show();
});

$(".close, .modal").click(function (event) {
    if (event.target === $("#addNetworkSettingsModal")[0] || $(event.target).hasClass("close")) {
        $("#addNetworkSettingsModal").hide();
    }
});

document.getElementById('addForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append("action", "add");

    $.ajax({
        url: '../backend/controllers/NetworkSettings_add.php',
        type: 'POST',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function(data) {
            alert("Настройки сети успешно добавлены!");
            $("#addNetworkSettingsModal").hide();
            $("#addForm")[0].reset();
        },
        error: function(xhr, status, error) {
            console.error('Error adding network settings:', error);
            alert("Ошибка при добавлении настроек сети.");
        }
    });
});
//Удаление настроек сети
$(document).on("click", ".delete-btn", function () {
    const networkSettingsId = $(this).data("id");

    if (confirm("Вы уверены, что хотите удалить эти настройки сети?")) {
        const formData = new FormData();
        formData.append("action", "delete");
        formData.append("id", networkSettingsId);

        $.ajax({
            url: '../backend/controllers/NetworkSettings_add.php',
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    let result = JSON.parse(response);
                    if (result.status === "true") {
                        alert("Настройки сети успешно удалены");
                        $(`tr[id='${networkSettingsId}']`).remove();
                    }
                } catch (e) {
                    console.error("Invalid JSON response:", response);
                }
            },
            error: function(xhr, status, error) {
                console.error('Ошибка удаления:', error);
            }
        });
    }
});
//Редактирование настроек сети
$(document).on("click", ".edit-btn", function () {
    const networkSettingsId = $(this).data("id");
    $.ajax({
        url: '../backend/controllers/NetworkSettings_add.php',
        type: 'POST',
        data: { action: "getById", id: networkSettingsId },
        success: function(data) {
            let NetworkSettings = JSON.parse(data);

            $("#editId").val(NetworkSettings.id);
            $("#editEquipmentId").val(NetworkSettings.equipment_id);
            $("#editIpAddress").val(NetworkSettings.ip_address);
            $("#editSubnetMask").val(NetworkSettings.subnet_mask);
            $("#editGateway").val(NetworkSettings.gateway);
            $("#editDns_servers").val(NetworkSettings.dns_servers);

            $("#editNetworkSettingsModal").show();
        },
        error: function(xhr, status, error) {
            console.error('Error loading network settings for edit:', error);
        }
    });
});

$(".close, .modal").click(function (event) {
    if (event.target === $("#editNetworkSettingsModal") || $(event.target).hasClass("close")) {
        $("#editNetworkSettingsModal").hide();
    }
});

document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append("action", "update");

    $.ajax({
        url: '../backend/controllers/NetworkSettings_add.php',
        type: 'POST',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function(data) {
            alert("Настройки сети успешно обновлены!");
            $("#editNetworkSettingsModal").hide();
        },
        error: function(xhr, status, error) {
            console.error('Error updating network settings:', error);
            alert("Ошибка при обновлении настроек сети.");
        }
    });
});
// Функционал поиска
const $searchBox = $('.search-box');
const $tbody = $('tbody');
const $table = $('table');
let debounceId;

function filterRows() {
    const q = $searchBox.val().trim().toLowerCase();

    $tbody.find('.no-results').remove();

    let visibleCount = 0;
    $tbody.find('tr').each(function() {
        const text = $(this).text().toLowerCase();
        if (q === '' || text.includes(q)) {
            $(this).show();
            visibleCount++;
        } else {
            $(this).hide();
        }
    });

    // если ничего не найдено
    if (q !== '' && visibleCount === 0) {
        const colCount = $table.find('thead th').length;
        const $noRow = $('<tr>').addClass('no-results');
        $noRow.append(
            $('<td>')
                .attr('colspan', colCount)
                .css({ 'text-align': 'center', 'padding': '10px', 'color': '#666' })
                .text('Ничего не найдено')
        );
        $tbody.append($noRow);
    }
}

// Запуск поиска по нажатию Enter
$searchBox.on('keydown', function(e) {
    if (e.key === 'Enter') {
        clearTimeout(debounceId);
        debounceId = setTimeout(filterRows, 300);
    }
});
  // Сортировка
    let lastSortedIndex = null;
    let lastSortDir = 'asc';

    function sortRows(index, direction) {
        // Убираем сообщение "ничего не найдено" перед сортировкой
        const $noResults = $tbody.find('.no-results').remove();

        // Получаем все строки кроме служебных
        const rows = $tbody.find('tr').not('.no-results').get();

        rows.sort(function(a, b) {
            let aText = $(a).children().eq(index).text().trim();
            let bText = $(b).children().eq(index).text().trim();

            // Распознаем данные
            const aDate = Date.parse(aText);
            const bDate = Date.parse(bText);
            let aVal, bVal;

            if (!isNaN(aDate) && !isNaN(bDate)) {
                aVal = aDate;
                bVal = bDate;
            } else if (!isNaN(parseFloat(aText)) && !isNaN(parseFloat(bText))) {
                aVal = parseFloat(aText);
                bVal = parseFloat(bText);
            } else {
                aVal = aText.toLowerCase();
                bVal = bText.toLowerCase();
            }

            if (aVal < bVal) return direction === 'asc' ? -1 : 1;
            if (aVal > bVal) return direction === 'asc' ? 1 : -1;
            return 0;
        });

        // Добавляем отсортированные строки обратно
        $.each(rows, function(_, row) {
            $tbody.append(row);
        });

        // Возвращаем сообщение "ничего не найдено", если было
        if ($noResults.length) $tbody.append($noResults);
    }

    // Обработка клика по заголовкам
    $table.find('thead th').each(function(index) {
        // Добавляем иконку-сортировку
        $(this).append('<span class="sort-icon" style="margin-left:5px; cursor:pointer;">▼</span>');
        $(this).css('cursor', 'pointer');

        $(this).on('click', function() {
            // Определяем направление
            let dir = 'asc';
            if (lastSortedIndex === index && lastSortDir === 'asc') {
                dir = 'desc';
            }

            $table.find('.sort-icon').text('▼');

            // Устанавливаем иконку для текущего
            const icon = dir === 'asc' ? '▲' : '▼';
            $(this).find('.sort-icon').text(icon);

            // Сортируем
            sortRows(index, dir);

            lastSortedIndex = index;
            lastSortDir = dir;
        });
    });