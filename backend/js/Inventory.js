//Выгрузка инвентаризации из бд
$(document).ready(function() {
    function loadInventory() {
        let formData = new FormData();
        formData.append("action", "get");

        $.ajax({
            url: '../backend/controllers/Inventory_add.php',
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(data) {
                let Inventorys = JSON.parse(data);
                Inventorys.forEach(Inventory => {
                    $("tbody").append(`
                    <tr id="${Inventory.id}">
                        <td>${Inventory.id}</td>
                        <td>${Inventory.name}</td>
                        <td>${Inventory.start_date}</td>
                        <td>${Inventory.end_date}</td>
                        <td>${Inventory.created_by}</td>
                        <td class=action-icons>
                            <img src=../img/edit.png alt=Edit class="edit-btn" data-id="${Inventory.id}">
                            <img src=../img/delete.png alt=Delete class="delete-btn" data-id="${Inventory.id}">
                        </td>
                    </tr>`)
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading Inventory:', error);
                alert("Ошибка при загрузке инвентаризации.");
            }
        });
    }
    loadInventory();
});
//Добавление инвентаризации
$(".btn-add").click(function () {
    $("#addInventoryModal").show();
});

$(".close, .modal").click(function (event) {
    if (event.target === $("#addInventoryModal")[0] || $(event.target).hasClass("close")) {
        $("#addInventoryModal").hide();
    }
});

document.getElementById('addForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append("action", "add");

    $.ajax({
        url: '../backend/controllers/Inventory_add.php',
        type: 'POST',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function(data) {
            alert("Инвентаризация успешно добавлена!");
            $("#addInventoryModal").hide();
            $("#addForm")[0].reset();
            location.reload();
        },
        error: function(xhr, status, error) {
            console.error('Error adding inventory:', error);
            alert("Ошибка при добавлении инвентаризации.");
        }
    });
});
//Удаление инвентаризации
$(document).on("click", ".delete-btn", function () {
    const inventoryId = $(this).data("id");

    if (confirm("Вы уверены, что хотите удалить эту инвентаризацию?")) {
        const formData = new FormData();
        formData.append("action", "delete");
        formData.append("id", inventoryId);

        $.ajax({
            url: '../backend/controllers/Inventory_add.php',
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
                        $(`tr[id='${inventoryId}']`).remove();
                    }
                } catch (e) {
                    console.error("Invalid JSON response:", response);
                    alert("Ошибка при обработке ответа сервера.");
                }
            },
            error: function(xhr, status, error) {
                console.error('Ошибка удаления:', error);
                alert("Ошибка при удалении инвентаризации.");
            }
        });
    }
});
//Редактирование инвентаризации
$(document).on("click", ".edit-btn", function () {
    const inventoryId = $(this).data("id");
    $.ajax({
        url: '../backend/controllers/Inventory_add.php',
        type: 'POST',
        data: { action: "getById", id: inventoryId },
        success: function(data) {
            let Inventory = JSON.parse(data);

            $("#editId").val(Inventory.id);
            $("#editName").val(Inventory.name);
            $("#editStartDate").val(Inventory.start_date);
            $("#editEndDate").val(Inventory.end_date);
            $("#editCreatedBy").val(Inventory.created_by);

            $("#editInventoryModal").show();
        },
        error: function(xhr, status, error) {
            console.error('Error loading inventory for edit:', error);
            alert("Ошибка при загрузке инвентаризации для редактирования.");
        }
    });
});

$(".close, .modal").click(function (event) {
    if (event.target === $("#editInventoryModal") || $(event.target).hasClass("close")) {
        $("#editInventoryModal").hide();
    }
});

document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append("action", "update");

    $.ajax({
        url: '../backend/controllers/Inventory_add.php',
        type: 'POST',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function(data) {
            alert("Инвентаризация успешно обновлена!");
            $("#editInventoryModal").hide();
            location.reload();
        },
        error: function(xhr, status, error) {
            console.error('Error updating inventory:', error);
            alert("Ошибка при обновлении инвентаризации.");
        }
    });
});