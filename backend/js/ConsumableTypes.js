$(function() {
    // Загрузить все типы
    function loadTypes() {
        $.post('../backend/controllers/ConsumableTypes_add.php', { action: 'get' }, function(data) {
            let types = JSON.parse(data);
            $('tbody').empty();
            types.forEach(t => {
                $('tbody').append(
                    `<tr id="${t.id}">
                        <td>${t.id}</td>
                        <td>${t.name}</td>
                        <td>
                            <img src="../img/edit.png" class="edit-btn" data-id="${t.id}">
                            <img src="../img/delete.png" class="delete-btn" data-id="${t.id}">
                        </td>
                    </tr>`
                );
            });
        });
    }
    loadTypes();

    // Открыть окно добавления
    $('.btn-add').click(() => $('#addTypeModal').show());
    // Закрыть модальные окна
    $('.close, .modal').click(e => {
        if ($(e.target).hasClass('modal') || $(e.target).hasClass('close')) $('.modal').hide();
    });

    // Добавление нового типа
    $('#addForm').submit(function(e) {
        e.preventDefault();
        $.post(
            '../backend/controllers/ConsumableTypes_add.php',
            $(this).serialize() + '&action=add',
            () => { $('.modal').hide(); loadTypes(); }
        );
    });

    // Удаление типа
    $(document).on('click', '.delete-btn', function() {
        if (confirm('Удалить тип расходника?')) {
            $.post(
                '../backend/controllers/ConsumableTypes_add.php',
                { action: 'delete', id: $(this).data('id') },
                loadTypes
            );
        }
    });
//Редактирование инвентаризации
            $(document).on("click", ".edit-btn", function () {
                const id = $(this).data("id");
                $.ajax({
                    url: '../backend/controllers/ConsumableTypes_add.php',
                    type: 'POST',
                    data: { action: "getById", id: id},
                    success: function(data) {
                        let t = JSON.parse(data);

                        $("#editid").val(t.id);
                        $("#editname").val(t.name);

                        $("#editTypeModal").show();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading direction for edit:', error);
                        alert("Ошибка при загрузке направления для редактирования.");
                    }
                });
            });

            $(".close, .modal").click(function (event) {
                if (event.target === $("#editTypeModal") || $(event.target).hasClass("close")) {
                    $("#editDirecteditTypeModalionModal").hide();
                }
            });

            document.getElementById('editForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                formData.append("action", "update");

                $.ajax({
                    url: '../backend/controllers/ConsumableTypes_add.php',
                    type: 'POST',
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        alert("Инвентаризация успешно обновлена!");
                        $("#editTypeModal").hide();
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating direction:', error);
                        alert("Ошибка при обновлении направления.");
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
})});
