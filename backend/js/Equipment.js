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
                        <td>${equipment.room_id}</td>
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
            $("#editPhotoPath").val(equipment.photo);
            $("#editInventoryNumber").val(equipment.inventory_number);
            $("#editCost").val(equipment.cost);
            $("#editDirection").val(equipment.direction_id);
            $("#editStatus").val(equipment.status_id);
            $("#editModel").val(equipment.model_id);
            $("#editComment").val(equipment.comment);
            $("#editClassroomId").val(equipment.room_id);
            $("#editresponsible_user_id").val(equipment.responsible_user_id);
            $("#edittemp_responsible_user_id").val(equipment.temp_responsible_user_id);

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