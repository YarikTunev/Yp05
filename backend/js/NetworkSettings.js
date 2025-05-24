$(document).ready(function() {
    function loadNetworkSettings() {
        let params = new FormData();
        params.append("action", "get");

        $.ajax({
            url: '../backend/controllers/NetworkSettings_add.php',
            type: 'POST',
            data: params,
            cache: false,
            processData: false,
            contentType: false,
            success: function(data) {
                let NetworkSettingss = JSON.parse(data);
                NetworkSettingss.forEach(NetworkSettings => {
                    $("tbody").append(`
                    <tr id=${NetworkSettings.id}>
                        <td>${NetworkSettings.id}</td>
                        <td>${NetworkSettings.equipment_id}</td>
                        <td>${NetworkSettings.ip_address}</td>
                        <td>${NetworkSettings.subnet_mask}</td>
                        <td>${NetworkSettings.gateway}</td>
                        <td>${NetworkSettings.dns1}</td>
                        <td>${NetworkSettings.dns2}</td>
                        <td class=action-icons>
                            <img src=../img/edit.png alt=Edit class="edit-btn" data-id="${NetworkSettings.id}">
                            <img src=../img/delete.png alt=Delete class="delete-btn" data-id="${NetworkSettings.id}">
                        </td>
                    </tr>`)
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading NetworkSettings:', error);
            }
        });
    }
    loadNetworkSettings();
});
//Добавление настроек сети
$(".btn-add").click(function () {
    $("#addNetworkSettingsModal").show();
});

$(".close, .modal").click(function (event) {
    if (event.target === $("#addNetworkSettingsModal")[0] || $(event.target).hasClass("close")) {
        $("#addNetworkSettingsModal").hide();
    }
});

document.getElementById('addForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append("action", "add");

    $.ajax({
        url: '../backend/controllers/NetworkSettings_add.php',
        type: 'POST',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function(data) {
            alert("Настройки сети успешно добавлены!");
            $("#addNetworkSettingsModal").hide();
            $("#addForm")[0].reset();
        },
        error: function(xhr, status, error) {
            console.error('Error adding network settings:', error);
            alert("Ошибка при добавлении настроек сети.");
        }
    });
});
//Удаление настроек сети
$(document).on("click", ".delete-btn", function () {
    const networkSettingsId = $(this).data("id");

    if (confirm("Вы уверены, что хотите удалить эти настройки сети?")) {
        const formData = new FormData();
        formData.append("action", "delete");
        formData.append("id", networkSettingsId);

        $.ajax({
            url: '../backend/controllers/NetworkSettings_add.php',
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    let result = JSON.parse(response);
                    if (result.status === "true") {
                        alert("Настройки сети успешно удалены");
                        $(`tr[id='${networkSettingsId}']`).remove();
                    }
                } catch (e) {
                    console.error("Invalid JSON response:", response);
                }
            },
            error: function(xhr, status, error) {
                console.error('Ошибка удаления:', error);
            }
        });
    }
});
//Редактирование настроек сети
$(document).on("click", ".edit-btn", function () {
    const networkSettingsId = $(this).data("id");
    $.ajax({
        url: '../backend/controllers/NetworkSettings_add.php',
        type: 'POST',
        data: { action: "getById", id: networkSettingsId },
        success: function(data) {
            let NetworkSettings = JSON.parse(data);

            $("#editId").val(NetworkSettings.id);
            $("#editEquipmentId").val(NetworkSettings.equipment_id);
            $("#editIpAddress").val(NetworkSettings.ip_address);
            $("#editSubnetMask").val(NetworkSettings.subnet_mask);
            $("#editGateway").val(NetworkSettings.gateway);
            $("#editDns1").val(NetworkSettings.dns1);
            $("#editDns2").val(NetworkSettings.dns2);

            $("#editNetworkSettingsModal").show();
        },
        error: function(xhr, status, error) {
            console.error('Error loading network settings for edit:', error);
        }
    });
});

$(".close, .modal").click(function (event) {
    if (event.target === $("#editNetworkSettingsModal") || $(event.target).hasClass("close")) {
        $("#editNetworkSettingsModal").hide();
    }
});

document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append("action", "update");

    $.ajax({
        url: '../backend/controllers/NetworkSettings_add.php',
        type: 'POST',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function(data) {
            alert("Настройки сети успешно обновлены!");
            $("#editNetworkSettingsModal").hide();
        },
        error: function(xhr, status, error) {
            console.error('Error updating network settings:', error);
            alert("Ошибка при обновлении настроек сети.");
        }
    });
});