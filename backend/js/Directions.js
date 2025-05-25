//Выгрузка инвентаризации из бд
$(document).ready(function() {
    function loadInventory() {
        let formData = new FormData();
        formData.append("action", "get");

        $.ajax({
            url: '../backend/controllers/Directions_add.php',
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(data) {
                let Disrections = JSON.parse(data);
                Disrections.forEach(Disrection => {
                    $("tbody").append(`
                    <tr id="${Disrection.id}">
                        <td>${Disrection.id}</td>
                        <td>${Disrection.name}</td>
                        <td class=action-icons>
                            <img src="../img/edit.png" alt="Edit" class="edit-btn" data-id="${Disrection.id}">
                            <img src="../img/delete.png" alt="Delete" class="delete-btn" data-id="${Disrection.id}">
                        </td>
                    </tr>`)
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading directions:', error);
                alert("Ошибка при загрузке направлений.");
            }
        });
    }
    loadInventory();
});
//Добавление инвентаризации
$(".btn-add").click(function () {
    $("#addDirectionModal").show();
});

$(".close, .modal").click(function (event) {
    if (event.target === $("#addDirectionModal")[0] || $(event.target).hasClass("close")) {
        $("#addDirectionModal").hide();
    }
});

document.getElementById('addForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append("action", "add");

    $.ajax({
        url: '../backend/controllers/Directions_add.php',
        type: 'POST',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function(data) {
            alert("Инвентаризация успешно добавлена!");
            $("#addDirectionModal").hide();
            $("#addDirectionForm")[0].reset();
            location.reload();
        },
        error: function(xhr, status, error) {
            console.error('Error adding directions:', error);
            alert("Ошибка при добавлении направлении.");
        }
    });
});
//Удаление инвентаризации
$(document).on("click", ".delete-btn", function () {
    const directionsId = $(this).data("id");

    if (confirm("Вы уверены, что хотите удалить эту инвентаризацию?")) {
        const formData = new FormData();
        formData.append("action", "delete");
        formData.append("id", directionsId);

        $.ajax({
            url: '../backend/controllers/Directions_add.php',
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    let result = JSON.parse(response);
                    if (result.status === "true") {
                        alert("Инвентаризация успешно удалена");
                        $(`tr[id='${directionsId}']`).remove();
                    }
                } catch (e) {
                    console.error("Invalid JSON response:", response);
                    alert("Ошибка при обработке ответа сервера.");
                }
            },
            error: function(xhr, status, error) {
                console.error('Ошибка удаления:', error);
                alert("Ошибка при удалении направлении.");
            }
        });
    }
});
//Редактирование инвентаризации
$(document).on("click", ".edit-btn", function () {
    const directionsId = $(this).data("id");
    $.ajax({
        url: '../backend/controllers/Directions_add.php',
        type: 'POST',
        data: { action: "getById", id: directionsId},
        success: function(data) {
            let Disrection = JSON.parse(data);

            $("#editid").val(Disrection.id);
            $("#editname").val(Disrection.name);

            $("#editDirectionModal").show();
        },
        error: function(xhr, status, error) {
            console.error('Error loading direction for edit:', error);
            alert("Ошибка при загрузке направления для редактирования.");
        }
    });
});

$(".close, .modal").click(function (event) {
    if (event.target === $("#editDirectionModal") || $(event.target).hasClass("close")) {
        $("#editDirectionModal").hide();
    }
});

document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append("action", "update");

    $.ajax({
        url: '../backend/controllers/Directions_add.php',
        type: 'POST',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function(data) {
            alert("Инвентаризация успешно обновлена!");
            $("#editDirectionModal").hide();
            location.reload();
        },
        error: function(xhr, status, error) {
            console.error('Error updating direction:', error);
            alert("Ошибка при обновлении направления.");
        }
    });
});