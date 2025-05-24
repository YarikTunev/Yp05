<?php
require_once ("../classes/equipmentmovementhistory.php");
require_once ("../../connection.php");

$action = $_POST["action"];
if($action == "get"){
    echo json_encode(EquipmentMovementHistory::Get(), JSON_UNESCAPED_UNICODE);
} else if($action == "getById"){
    echo json_encode(EquipmentMovementHistory::GetById($_POST["id"]), JSON_UNESCAPED_UNICODE);
} else if ($action == "add") {
    $equipmentMovementHistory = new EquipmentMovementHistory($_POST);
    $params = $equipmentMovementHistory->Add();
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
} else if ($action == "update") {
    $equipmentMovementHistory = new EquipmentMovementHistory($_POST);
    $params = $equipmentMovementHistory->Update();
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
} else if ($action == "delete"){
    $equipmentMovementHistory = new EquipmentMovementHistory($_POST);
    $params = $equipmentMovementHistory->Delete();
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
}
?>
