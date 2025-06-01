<?php
require_once(__DIR__ . "/../classes/equipment.php");
require_once(__DIR__ . "/../../connection.php");

if (!isset($_POST['action'])) {
    echo json_encode(['error' => 'Action parameter is missing'], JSON_UNESCAPED_UNICODE);
    exit;
}

$action = $_POST['action'];

if ($action == "get") {
    echo json_encode(Equipment::Get(), JSON_UNESCAPED_UNICODE);
} else if($action == "getById") {
    echo json_encode(Equipment::GetById($_POST['id']), JSON_UNESCAPED_UNICODE);
} elseif ($action == "add") {
    $equipment = new Equipment($_POST);
    $params = $equipment->Add();
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
} elseif ($action == "update") {
    $equipment = new Equipment($_POST);
    $params = $equipment->Update();
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
} elseif ($action == "delete") {
    $equipment = new Equipment($_POST);
    $result = $equipment->Delete();
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
?>