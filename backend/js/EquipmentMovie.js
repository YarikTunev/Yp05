//Выгрузка истории перемещения оборудования из бд
$(document).ready(function() {
    function loadEquipmentMovementHistory() {
        let params = new FormData();
        params.append("action", "get");

        $.ajax({
            url: '../backend/controllers/equipmentmovementhistory_add.php',
            type: 'POST',
            data: params,
            cache: false,
            processData: false,
            contentType: false,
            success: function(data) {
                let EquipmentMovementHistorys = JSON.parse(data);
                EquipmentMovementHistorys.forEach(EquipmentMovementHistory => {
                    $("tbody").append(`
                    <tr id="${EquipmentMovementHistory.id}">
                        <td>${EquipmentMovementHistory.id}</td>
                        <td>${EquipmentMovementHistory.equipment_id}</td>
                        <td>${EquipmentMovementHistory.classroom_id}</td>
                        <td>${EquipmentMovementHistory.responsible_user_id}</td>
                        <td>${EquipmentMovementHistory.temp_responsible_user_id}</td>
                        <td>${EquipmentMovementHistory.movement_date}</td>
                        <td>${EquipmentMovementHistory.comment}</td>
                        <td class=action-icons>
                            <img src=../img/edit.png alt=Edit class="edit-btn" data-id="${EquipmentMovementHistory.id}">
                            <img src=../img/delete.png alt=Delete class="delete-btn" data-id="${EquipmentMovementHistory.id}">
                        </td>
                    </tr>`)
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading EquipmentMovementHistory:', error);
            }
        });
    }
    loadEquipmentMovementHistory();
});
//Добавление истории перемещения оборудования
$(".btn-add").click(function () {
    $("#addEquipmentMovementHistoryModal").show();
});

$(".close, .modal").click(function (event) {
    if (event.target === $("#addEquipmentMovementHistoryModal")[0] || $(event.target).hasClass("close")) {
        $("#addEquipmentMovementHistoryModal").hide();
    }
});

document.getElementById('addForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append("action", "add");

    $.ajax({
        url: '../backend/controllers/equipmentmovementhistory_add.php',
        type: 'POST',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function(data) {
            alert("История перемещения оборудования успешно добавлена!");
            $("#addEquipmentMovementHistoryModal").hide();
            $("#addForm")[0].reset();
            location.reload();
        },
        error: function(xhr, status, error) {
            console.error('Error adding equipment movement history:', error);
            alert("Ошибка при добавлении истории перемещения оборудования.");
        }
    });
});
//Удаление истории перемещения оборудования
$(document).on("click", ".delete-btn", function () {
    const equipmentMovementHistoryId = $(this).data("id");

    if (confirm("Вы уверены, что хотите удалить эту запись истории перемещения оборудования?")) {
        const formData = new FormData();
        formData.append("action", "delete");
        formData.append("id", equipmentMovementHistoryId);

        $.ajax({
            url: '../backend/controllers/equipmentmovementhistory_add.php',
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    let result = JSON.parse(response);
                    if (result.status === "true") {
                        alert("История перемещения оборудования успешно удалена");
                        $(`tr[id='${equipmentMovementHistoryId}']`).remove();
                    }
                } catch (e) {
                    console.error("Invalid JSON response:", response);
                    alert("Ошибка при обработке ответа сервера.");
                }
            },
            error: function(xhr, status, error) {
                console.error('Ошибка удаления:', error);
            }
        });
    }
});
//Редактирование истории перемещения оборудования
$(document).on("click", ".edit-btn", function () {
    const equipmentMovementHistoryId = $(this).data("id");
    $.ajax({
        url: '../backend/controllers/equipmentmovementhistory_add.php',
        type: 'POST',
        data: { action: "getById", id: equipmentMovementHistoryId },
        success: function(data) {
            let EquipmentMovementHistory = JSON.parse(data);

            $("#editId").val(EquipmentMovementHistory.id);
            $("#editEquipmentId").val(EquipmentMovementHistory.equipment_id);
            $("#editClassroomId").val(EquipmentMovementHistory.classroom_id);
            $("#editResponsibleUserId").val(EquipmentMovementHistory.responsible_user_id);
            $("#editTempResponsibleUserId").val(EquipmentMovementHistory.temp_responsible_user_id);
            $("#editMovementDate").val(EquipmentMovementHistory.movement_date);
            $("#editComment").val(EquipmentMovementHistory.comment);

            $("#editEquipmentMovementHistoryModal").show();
        },
        error: function(xhr, status, error) {
            console.error('Error loading equipment movement history for edit:', error);
        }
    });
});

$(".close, .modal").click(function (event) {
    if (event.target === $("#editEquipmentMovementHistoryModal") || $(event.target).hasClass("close")) {
        $("#editEquipmentMovementHistoryModal").hide();
    }
});

document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append("action", "update");

    $.ajax({
        url: '../backend/controllers/equipmentmovementhistory_add.php',
        type: 'POST',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function(data) {
            alert("История перемещения оборудования успешно обновлена!");
            $("#editEquipmentMovementHistoryModal").hide();
            location.reload();
        },
        error: function(xhr, status, error) {
            console.error('Error updating equipment movement history:', error);
            alert("Ошибка при обновлении истории перемещения оборудования.");
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