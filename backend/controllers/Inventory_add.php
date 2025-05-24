<?php
require_once ("../classes/Inventory.php");
require_once ("../../connection.php");

$action = $_POST["action"];
if($action == "get"){
    echo json_encode(Inventory::Get(), JSON_UNESCAPED_UNICODE);
} else if($action == "getById"){
    echo json_encode(Inventory::GetById($_POST["id"]), JSON_UNESCAPED_UNICODE);
} else if ($action == "add") {
    $inventory = new Inventory($_POST);
    $params = $inventory->Add();
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
} else if ($action == "update") {
    $inventory = new Inventory($_POST);
    $params = $inventory->Update();
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
} else if ($action == "delete"){
    $inventory = new Inventory($_POST);
    $params = $inventory->Delete();
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
}
?>
