<?php
require_once("../classes/Consumables.php");
require_once("../../connection.php");

$action = $_POST['action'];

if($action == "get") {
    echo json_encode(Consumables::Get(), JSON_UNESCAPED_UNICODE);
} else if($action == "getById") {
    echo json_encode(Consumables::GetById($_POST['id']), JSON_UNESCAPED_UNICODE);
} else if($action == "add") {
    $data = $_POST;
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $data['image'] = file_get_contents($_FILES['image']['tmp_name']);
    } else {
        $data['image'] = null;
    }
    $consumable = new Consumables($data);
    $result = $consumable->Add();
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
} else if($action == "update") {
    $data = $_POST;
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $data['image'] = file_get_contents($_FILES['image']['tmp_name']);
    } else {
        $data['image'] = null;
    }
    $consumable = new Consumables($data);
    $result = $consumable->Update();
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
} else if($action == "delete") {
    $consumable = new Consumables($_POST);
    $result = $consumable->Delete();
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
?>