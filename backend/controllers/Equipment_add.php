<?php
require_once ("../classes/equipment.php");
require_once ("../../connection.php");

$action = $_POST["action"];
if($action == "get"){
    echo json_encode(Equipment::Get(), JSON_UNESCAPED_UNICODE);
} else if($action == "getById"){
    echo json_encode(Equipment::GetById($_POST["id"]), JSON_UNESCAPED_UNICODE);
} else if ($action == "add") {
    $equipment = new Equipment($_POST);
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'C:\Users\student-a502\Desktop\ospanel_2024_min_8.0\domains\localhost\Up05\backend\uploads/';
        $uploadFile = $uploadDir . basename($_FILES['photo']['name']);

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
            $equipment->photo = $uploadFile;
        } else {
            echo json_encode(['error' => 'Failed to upload photo'], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
    $params = $equipment->Add();
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
} else if ($action == "update") {
    $equipment = new Equipment($_POST);
    $params = $equipment->Update();
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
} else if ($action == "delete"){
    $equipment = new Equipment($_POST);
    $params = $equipment->Delete();
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
}
?>
