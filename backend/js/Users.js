//Выгрузка пользователя из бд
$(document).ready(function() {
    function loadUsers() {
        let params = new FormData();
        params.append("action", "get");

        $.ajax({
            url: '../backend/controllers/Users_add.php',
            type: 'POST',
            data: params,
            cache: false,
            processData: false,
            contentType: false,
            success: function(data) {
                let Users= JSON.parse(data);

                Users.forEach(User => {

                    $("tbody").append(`
                    <tr id=${User.id}>
                        <td>${User.id}</td>
                        <td>${User.login}</td>
                        <td>${User.password}</td>
                        <td>${User.role}</td>
                        <td>${User.email}</td>
                        <td>${User.last_name}</td>
                        <td>${User.first_name}</td>
                        <td>${User.middle_name}</td>
                        <td>${User.phone}</td>
                        <td>${User.address}</td>
                        <td class=action-icons>
                            <img src=../img/edit.png  alt=Edit class="edit-btn" data-id="${User.id}">
                            <img src="../img/delete.png" alt="Delete" class="delete-btn" data-id="${User.id}">
                        </td>
                    </tr>`)
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading Users:', error);
            }
        });
        
    }
    loadUsers();
});
//Добавление пользователя
$(".btn-add").click(function () {
        $("#addUserModal").show();
    });

    $(".close, .modal").click(function (event) {
        if (event.target === $("#addUserModal")[0] || $(event.target).hasClass("close")) {
            $("#addUserModal").hide();
        }
    });
   document.getElementById('addForm').addEventListener('submit', function(e) {
        e.preventDefault();
    const formData = new FormData(this);
    formData.append("action", "add");

    $.ajax({
        url: '../backend/controllers/Users_add.php',
        type: 'POST',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function(data) {
            alert("Пользователь успешно добавлен!");
            $("#addUserModal").hide();
            $("#addForm")[0].reset();
            
        },
        error: function(xhr, status, error) {
            console.error('Error adding user:', error);
            alert("Ошибка при добавлении пользователя.");
        },
    });
    });
//Удаление пользователя
$(document).on("click", ".delete-btn", function () {
    const userId = $(this).data("id");

    if (confirm("Вы уверены, что хотите удалить этого пользователя?")) {
        const formData = new FormData();
        formData.append("action", "delete");
        formData.append("id", userId);

        $.ajax({
            url: '../backend/controllers/Users_add.php',
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    let result = JSON.parse(response);
                    if (result.status === "true") {
                        alert("Пользователь успешно удален");
                        $(`tr[id='${userId}']`).remove();
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
//Редактирования пользователя
$(document).on("click", ".edit-btn", function () {
    const userId = $(this).data("id");
    $.ajax({
        url: '../backend/controllers/Users_add.php',
        type: 'POST',
        data: { action: "getById", id: userId },
        success: function(data) {
            console.log(data);
            let User = JSON.parse(data);

            $("#editId").val(User.id);
            $("#editLogin").val(User.login);
            $("#editPassword").val(User.password);
            $("#editRole").val(User.role);
            $("#editEmail").val(User.email);
            $("#editLastName").val(User.last_name);
            $("#editFirstName").val(User.first_name);
            $("#editMiddleName").val(User.middle_name);
            $("#editPhone").val(User.phone);
            $("#editAddress").val(User.address);

            $("#editUserModal").show();
        },
        error: function(xhr, status, error) {
            console.error('Error loading user for edit:', error);
        }
    });
});

$(".close, .modal").click(function (event) {
    if (event.target === $("#editUserModal") || $(event.target).hasClass("close")) {
        $("#editUserModal").hide();
    }
});

document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append("action", "update");

    $.ajax({
        url: '../backend/controllers/Users_add.php',
        type: 'POST',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function(data) {
            alert("Пользователь успешно обновлен!");
            $("#editUserModal").hide();
        },
        error: function(xhr, status, error) {
            console.error('Error updating user:', error);
            alert("Ошибка при обновлении пользователя.");
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