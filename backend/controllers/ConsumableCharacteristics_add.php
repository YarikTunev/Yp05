<?php
require_once("../classes/ConsumableCharacteristics.php");
require_once("../../connection.php");

$action = $_POST['action'];

if($action == 'get') {
    echo json_encode(ConsumableCharacteristics::Get(), JSON_UNESCAPED_UNICODE);
} elseif($action == 'getById') {
    echo json_encode(ConsumableCharacteristics::GetById($_POST['id']), JSON_UNESCAPED_UNICODE);
} elseif($action == 'add') {
    $o = new ConsumableCharacteristics($_POST);
    echo json_encode($o->Add(), JSON_UNESCAPED_UNICODE);
} elseif($action == 'update') {
    $o = new ConsumableCharacteristics($_POST);
    echo json_encode($o->Update(), JSON_UNESCAPED_UNICODE);
} elseif($action == 'delete') {
    $o = new ConsumableCharacteristics($_POST);
    echo json_encode($o->Delete(), JSON_UNESCAPED_UNICODE);
}
?>