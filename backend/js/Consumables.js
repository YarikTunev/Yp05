$(document).ready(function() {
    // загрузка списка расходников
    function loadConsumables() {
        let params = new FormData();
        params.append("action", "get");
        $.ajax({
            url: '../backend/controllers/Consumables_add.php',
            type: 'POST',
            data: params,
            cache: false,
            processData: false,
            contentType: false,
            success: function(data) {
                let consumables = JSON.parse(data);
                consumables.forEach(consumable => {
                    $("tbody").append(`
                        <tr id=${consumable.id}>
                            <td>${consumable.id}</td>
                            <td>${consumable.name}</td>
                            <td>${consumable.description}</td>
                            <td>${consumable.date_received}</td>
                            <td>${consumable.image}</td>
                            <td>${consumable.quantity}</td>
                            <td>${consumable.cost}</td>
                            <td>${consumable.responsible_user_id}</td>
                            <td>${consumable.temp_responsible_user_id}</td>
                            <td>${consumable.consumable_type_id}</td>
                            <td class="action-icons">
                                <img src="../img/edit.png" alt="Edit" class="edit-btn" data-id="${consumable.id}">
                                <img src="../img/delete.png" alt="Delete" class="delete-btn" data-id="${consumable.id}">
                            </td>
                        </tr>
                    `);
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading consumables:', error);
            }
        });
    }
    loadConsumables();

    // показ модального окна добавления
    $('.btn-add').click(function() {
        $('#addConsumableModal').show();
    });

    // закрытие модальных окон 
    $('.close, .modal').click(function(event) {
        if ($(event.target).hasClass('modal') || $(event.target).hasClass('close')) {
            $('.modal').hide();
        }
    });

    // отправка формы добавления
    $('#addForm').submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'add');
        $.ajax({
            url: '../backend/controllers/Consumables_add.php',
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(data) {
                alert('Расходник успешно добавлен!');
                $('.modal').hide();
                $('#addForm')[0].reset();
                $('tbody').empty();
                loadConsumables();
            },
            error: function(xhr, status, error) {
                console.error('Error adding consumable:', error);
                alert('Ошибка при добавлении расходника.');
            }
        });
    });

    // удаление одного расходника
    $(document).on('click', '.delete-btn', function() {
        const consumableId = $(this).data('id');
        if (confirm('Вы уверены, что хотите удалить этот расходник?')) {
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('id', consumableId);
            $.ajax({
                url: '../backend/controllers/Consumables_add.php',
                type: 'POST',
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                success: function(response) {
                    let result;
                    try {
                        result = JSON.parse(response);
                    } catch (e) {
                        console.error('Invalid JSON response:', response);
                        alert('Ошибка при обработке ответа сервера.');
                        return;
                    }
                    if (result) {
                        alert('Расходник успешно удалён');
                        $('tbody').empty();
                        loadConsumables();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error deleting consumable:', error);
                }
            });
        }
    });

    // показ модального окна редактирования и загрузка данных
    $(document).on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        $.ajax({
            url: '../backend/controllers/Consumables_add.php',
            type: 'POST',
            data: { action: 'getById', id: id },
            success: function(data) {
                const consumable = JSON.parse(data);
                $('#editId').val(consumable.id);
                $('#editName').val(consumable.name);
                $('#editDescription').val(consumable.description);
                $('#editDateReceived').val(consumable.date_received);
                $('#editimage').val(consumable.image);
                $('#editQuantity').val(consumable.quantity);
                $('#editCost').val(consumable.cost);
                $('#editResponsibleUserId').val(consumable.responsible_user_id);
                $('#editTempResponsibleUserId').val(consumable.temp_responsible_user_id);
                $('#editConsumableTypeId').val(consumable.consumable_type_id);
                $('#editConsumableModal').show();
            },
            error: function(xhr, status, error) {
                console.error('Error loading consumable for edit:', error);
            }
        });
    });

    // отправка формы редактирования
    $('#editForm').submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'update');
        $.ajax({
            url: '../backend/controllers/Consumables_add.php',
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(data) {
                alert('Расходник успешно обновлён!');
                $('.modal').hide();
                $('#editForm')[0].reset();
                $('tbody').empty();
                loadConsumables();
            },
            error: function(xhr, status, error) {
                console.error('Error updating consumable:', error);
                alert('Ошибка при обновлении расходника.');
            }
        });
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