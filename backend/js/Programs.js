//Выгрузка пользователя из бд
$(document).ready(function() {
    function loadProgram() {
        let params = new FormData();
        params.append("action", "get");

        $.ajax({
            url: '../backend/controllers/Programs_add.php',
            type: 'POST',
            data: params,
            cache: false,
            processData: false,
            contentType: false,
            success: function(data) {
                let Programs= JSON.parse(data);

                Programs.forEach(Program => {

                    $("tbody").append(`
                    <tr id=${Program.id}>
                        <td>${Program.id}</td>
                        <td>${Program.name}</td>
                        <td>${Program.version}</td>
                        <td>${Program.developer}</td>
                        <td class=action-icons>
                            <img src=../img/edit.png alt=Edit class="edit-btn" data-id="${Program.id}">
                            <img src="../img/delete.png" alt="Delete" class="delete-btn" data-id="${Program.id}">
                        </td>
                    </tr>`)
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading Program:', error);
            }
        });
    }
    loadProgram();
});
//Добавление пользователя
$(".btn-add").click(function () {
        $("#addProgramModal").show();
    });

    $(".close, .modal").click(function (event) {
        if (event.target === $("#addProgramModal")[0] || $(event.target).hasClass("close")) {
            $("#addProgramModal").hide();
        }
    });
   document.getElementById('addForm').addEventListener('submit', function(e) {
        e.preventDefault();
    const formData = new FormData(this);
    formData.append("action", "add");

    $.ajax({
        url: '../backend/controllers/Programs_add.php',
        type: 'POST',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function(data) {
            alert("Программа успешно добавленa!");
            $("#addProgramModal").hide();
            $("#addForm")[0].reset();
            
        },
        error: function(xhr, status, error) {
            console.error('Error adding program:', error);
            alert("Ошибка при добавлении программы.");
        },
    });
    });
//Удаление пользователя
$(document).on("click", ".delete-btn", function () {
    const programId = $(this).data("id");

    if (confirm("Вы уверены, что хотите удалить эту программу?")) {
        const formData = new FormData();
        formData.append("action", "delete");
        formData.append("id", programId);

        $.ajax({
            url: '../backend/controllers/Programs_add.php',
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    let result = JSON.parse(response);
                    if (result.status === "true") {
                        alert("Программа успешно удалена");
                        $(`tr[id='${programId}']`).remove();
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
    const programId = $(this).data("id");
    $.ajax({
        url: '../backend/controllers/Programs_add.php',
        type: 'POST',
        data: { action: "getById", id: programId },
        success: function(data) {
            console.log(data);
            let Program = JSON.parse(data);

            $('#editid').val(Program.id);
            $("#editname").val(Program.name);
            $("#editversion").val(Program.version);
            $("#editdeveloper").val(Program.developer);
            $("#editProgramModal").show();
        },
        error: function(xhr, status, error) {
            console.error('Error loading program for edit:', error);
        }
    });
});

$(".close, .modal").click(function (event) {
    if (event.target === $("#editProgramModal") || $(event.target).hasClass("close")) {
        $("#editProgramModal").hide();
    }
});

document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append("action", "update");

    $.ajax({
        url: '../backend/controllers/Programs_add.php',
        type: 'POST',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function(data) {
            alert("Программа успешно обновлен!");
            $("#editProgramModal").hide();
        },
        error: function(xhr, status, error) {
            console.error('Error updating program:', error);
            alert("Ошибка при обновлении программы.");
        }
    });
});