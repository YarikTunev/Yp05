<?php
require_once ("../classes/InventoryResults.php");
require_once ("../../connection.php");

$action = $_POST["action"];
if($action == "get"){
    echo json_encode(InventoryResults::Get(), JSON_UNESCAPED_UNICODE);
} else if($action == "getById"){
    echo json_encode(InventoryResults::GetById($_POST["id"]), JSON_UNESCAPED_UNICODE);
} else if ($action == "add") {
    $inventoryResults = new InventoryResults($_POST);
    $params = $inventoryResults->Add();
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
} else if ($action == "update") {
    $inventoryResults = new InventoryResults($_POST);
    $params = $inventoryResults->Update();
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
} else if ($action == "delete"){
    $inventoryResults = new InventoryResults($_POST);
    $params = $inventoryResults->Delete();
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
}
?>
