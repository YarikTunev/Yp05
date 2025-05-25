//Выгрузка пользователя из бд
$(document).ready(function() {
    function loadStatus() {
        let params = new FormData();
        params.append("action", "get");

        $.ajax({
            url: '../backend/controllers/Status_add.php',
            type: 'POST',
            data: params,
            cache: false,
            processData: false,
            contentType: false,
            success: function(data) {
                let Statuss= JSON.parse(data);
                Statuss.forEach(Status => {
                    $("tbody").append(`
                     <tr id=${Status.id}>
                        <td>${Status.id}</td>
                        <td>${Status.name}</td>
                        <td class=action-icons>
                            <img src="../img/edit.png"  alt="Edit" class="edit-btn" data-id="${Status.id}">
                            <img src="../img/delete.png" alt="Delete" class="delete-btn" data-id="${Status.id}">
                        </td>
                    </tr>`)
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading Status:', error);
            }
        });
    }
    loadStatus();
});
//Добавление пользователя
$(".btn-add").click(function () {
        $("#addStatusModal").show();
    });

    $(".close, .modal").click(function (event) {
        if (event.target === $("#addStatusModal")[0] || $(event.target).hasClass("close")) {
            $("#addStatusModal").hide();
        }
    });
   document.getElementById('addForm').addEventListener('submit', function(e) {
        e.preventDefault();
    const formData = new FormData(this);
    formData.append("action", "add");

    $.ajax({
        url: '../backend/controllers/Status_add.php',
        type: 'POST',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function(data) {
            alert("Статус успешно добавлен!");
            $("#addStatusModal").hide();
            $("#addForm")[0].reset();
            
        },
        error: function(xhr, status, error) {
            console.error('Error adding Status:', error);
            alert("Ошибка при добавлении статуса.");
        },
    });
    });
//Удаление пользователя
$(document).on("click", ".delete-btn", function () {
    const statusId = $(this).data("id");

    if (confirm("Вы уверены, что хотите удалить этот статус?")) {
        const formData = new FormData();
        formData.append("action", "delete");
        formData.append("id", statusId);

        $.ajax({
            url: '../backend/controllers/Status_add.php',
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    let result = JSON.parse(response);
                    if (result.status === "true") {
                        alert("статус успешно удален");
                        $(`tr[id='${statusId}']`).remove();
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
    const statusId = $(this).data("id");
    $.ajax({
        url: '../backend/controllers/Status_add.php',
        type: 'POST',
        data: { action: "getById", id: statusId },
        success: function(data) {
            console.log(data);
            let Status = JSON.parse(data);

            $("#editid").val(Status.id);
            $("#editname").val(Status.name);
            $("#editStatusModal").show();
        },
        error: function(xhr, status, error) {
            console.error('Error loading Status for edit:', error);
        }
    });
});

$(".close, .modal").click(function (event) {
    if (event.target === $("#editStatusModal") || $(event.target).hasClass("close")) {
        $("#editStatusModal").hide();
    }
});

document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append("action", "update");

    $.ajax({
        url: '../backend/controllers/Status_add.php',
        type: 'POST',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function(data) {
            alert("статус успешно обновлен!");
            $("#editStatusModal").hide();
        },
        error: function(xhr, status, error) {
            console.error('Error updating Status:', error);
            alert("Ошибка при обновлении статуса.");
        }
    });
});