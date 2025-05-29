<?php
require_once "../classes/ConsumableTypes.php";
require_once "../../connection.php";

$action = $_POST['action'];

switch($action) {
    case 'get':
        echo json_encode(ConsumableTypes::Get(), JSON_UNESCAPED_UNICODE);
        break;
    case 'getById':
        echo json_encode(ConsumableTypes::GetById($_POST['id']), JSON_UNESCAPED_UNICODE);
        break;
    case 'add':
        $type = new ConsumableTypes($_POST);
        echo json_encode($type->Add(), JSON_UNESCAPED_UNICODE);
        break;
    case 'update':
        $type = new ConsumableTypes($_POST);
        echo json_encode($type->Update(), JSON_UNESCAPED_UNICODE);
        break;
    case 'delete':
        $type = new ConsumableTypes($_POST);
        echo json_encode($type->Delete(), JSON_UNESCAPED_UNICODE);
        break;
}