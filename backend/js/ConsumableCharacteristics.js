$(function(){
    function loadChars(){
        $.post('../backend/controllers/ConsumableCharacteristics_add.php',{action:'get'},function(data){
            let arr = JSON.parse(data);
            $('tbody').empty();
            arr.forEach(i=>{
                $('tbody').append(`
                    <tr id=${i.id}>
                        <td>${i.id}</td>
                        <td>${i.consumable_id}</td>
                        <td>${i.characteristic_name}</td>
                        <td>
                            <img src="../img/edit.png" class="edit-btn" data-id="${i.id}">
                            <img src="../img/delete.png" class="delete-btn" data-id="${i.id}">
                        </td>
                    </tr>
                `);
            });
        });
    }
    loadChars();

    $('.btn-add').click(()=>$('#addCharModal').show());
    $('.close').click(()=>$('.modal').hide());

    $('#addForm').submit(function(e){
        e.preventDefault();
        $.post('../backend/controllers/ConsumableCharacteristics_add.php',
            $(this).serialize()+'&action=add',
            ()=>{ $('.modal').hide(); loadChars(); }
        );
    });

    $(document).on('click','.delete-btn',function(){
        if(confirm('Удалить?')){
            $.post('../backend/controllers/ConsumableCharacteristics_add.php', {action:'delete', id:$(this).data('id')}, loadChars);
        }
    });

    $(document).on('click','.edit-btn',function(){
    let id = $(this).data('id');
    $.post('../backend/controllers/ConsumableCharacteristics_add.php', {action:'getById', id}, function(d){
        let o = JSON.parse(d); // исправлено: data -> d

        $('#consumable_id').val(o.consumable_id);
        $('#characteristic_name').val(o.characteristic_name);

        // Удаляем старый скрытый инпут id, если есть
        $('#addForm input[name="id"]').remove();

        // Добавляем новый
        $('#addForm').append(`<input type="hidden" name="id" value="${o.id}">`);

        // Отвязываем старый submit и привязываем новый
        $('#addForm').off('submit').on('submit', function(ev){
            ev.preventDefault();
            $.post('../backend/controllers/ConsumableCharacteristics_add.php', $(this).serialize()+'&action=update', function() {
                $('.modal').hide();
                loadChars();
            });
        });

        $('#addCharModal').show();
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