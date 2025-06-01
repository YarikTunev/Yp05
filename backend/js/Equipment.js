//Выгрузка оборудования из бд
$(document).ready(function() {
    function loadEquipments() {
        let formData = new FormData();
        formData.append("action", "get");

        $.ajax({
            url: '../backend/controllers/Equipment_add.php',
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(data) {
                let equipments = JSON.parse(data);
                equipments.forEach(equipment => {
                    $("tbody").append(`
                    <tr id="${equipment.id}">
                        <td>${equipment.id}</td>
                        <td>${equipment.name}</td>
                        <td><img src=${equipment.photo} alt="${equipment.name}"></td>
                        <td>${equipment.inventory_number}</td>
                        <td>${equipment.classroom_id}</td>
                        <td>${equipment.responsible_user_id}</td>
                        <td>${equipment.temp_responsible_user_id}</td>
                        <td>${equipment.cost}</td>
                        <td>${equipment.direction_id}</td>
                        <td>${equipment.status_id}</td>
                        <td>${equipment.model_id}</td>
                        <td>${equipment.comment}</td>
                        
                        
                        <td class=action-icons>
                            <img src=../img/edit.png alt=Edit class="edit-btn" data-id="${equipment.id}">
                            <img src=../img/delete.png alt=Delete class="delete-btn" data-id="${equipment.id}">
                        </td>
                    </tr>`)
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading equipments:', error);
                alert("Ошибка при загрузке оборудования.");
            }
        });
    }
    loadEquipments();
});
$(document).on("click", ".document-btn", function () {
    const equipmentId = $(this).data("id");

    let formData = new FormData();
    formData.append("action", "getById");
    formData.append("id", equipmentId);

    $.ajax({
        url: '../backend/controllers/Equipment_add.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            window.location.href = `../backend/generate_act.php?id=${equipmentId}`;
        },
        error: function(xhr, status, error) {
            alert("Ошибка при загрузке данных оборудования.");
            console.error(error);
        }
    });
});
//Добавление оборудования
$(".btn-add").click(function () {
    $("#addEquipmentModal").show();
});

$(".close, .modal").click(function (event) {
    if (event.target === $("#addEquipmentModal")[0] || $(event.target).hasClass("close")) {
        $("#addEquipmentModal").hide();
    }
});

document.getElementById('addForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append("action", "add");
    $.ajax({
        url: '../backend/controllers/Equipment_add.php',
        type: 'POST',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function(data) {
            console.log(data);
            alert("Оборудование успешно добавлено!");
            $("#addEquipmentModal").hide();
            $("#addForm")[0].reset();
            location.reload();
        },
        error: function(xhr, status, error) {
            console.error('Error adding equipment:', error);
            alert("Ошибка при добавлении оборудования.");
        }
    });
});
//Удаление оборудования
$(document).on("click", ".delete-btn", function () {
    const equipmentId = $(this).data("id");

    if (confirm("Вы уверены, что хотите удалить это оборудование?")) {
        const formData = new FormData();
        formData.append("action", "delete");
        formData.append("id", equipmentId);

        $.ajax({
            url: '../backend/controllers/Equipment_add.php',
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    let result = JSON.parse(response);
                    if (result.status === "true") {
                        alert("Оборудование успешно удалено");
                        $(`tr[id='${equipmentId}']`).remove();
                        location.reload();
                    }
                } catch (e) {
                    console.error("Invalid JSON response:", response);
                    alert("Ошибка при обработке ответа сервера.");
                }
            },
            error: function(xhr, status, error) {
                console.error('Ошибка удаления:', error);
                alert("Ошибка при удалении оборудования.");
            }
        });
    }
});
//Редактирование оборудования
$(document).on("click", ".edit-btn", function () {
    const equipmentId = $(this).data("id");
    $.ajax({
        url: '../backend/controllers/Equipment_add.php',
        type: 'POST',
        data: { action: "getById", id: equipmentId },
        success: function(data) {
            let equipment = JSON.parse(data);

            $("#editId").val(equipment.id);
            $("#editName").val(equipment.name);
            $("#editPhoto").val(equipment.photo);
            $("#editInventory_number").val(equipment.inventory_number);
            $("#editClassroom_id").val(equipment.classroom_id);
            $("#editResponsible_user_id").val(equipment.responsible_user_id);
            $("#editTemp_responsible_user_id").val(equipment.temp_responsible_user_id);
            $("#editCost").val(equipment.cost);
            $("#editDirection_id").val(equipment.direction_id);
            $("#editStatus_id").val(equipment.status_id);
            $("#editModel_id").val(equipment.model_id);
            $("#editComment").val(equipment.comment);
            $("#editEquipmentModal").show();
        },
        error: function(xhr, status, error) {
            console.error('Error loading equipment for edit:', error);
            alert("Ошибка при загрузке оборудования для редактирования.");
        }
    });
});

$(".close, .modal").click(function (event) {
    if (event.target === $("#editEquipmentModal") || $(event.target).hasClass("close")) {
        $("#editEquipmentModal").hide();
    }
});

document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append("action", "update");

    $.ajax({
        url: '../backend/controllers/Equipment_add.php',
        type: 'POST',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function(data) {
            alert("Оборудование успешно обновлено!");
            $("#editEquipmentModal").hide();
            location.reload();
        },
        error: function(xhr, status, error) {
            console.error('Error updating equipment:', error);
            alert("Ошибка при обновлении оборудования.");
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
