<?php
require_once("../classes/Models.php");
require_once("../../connection.php");

$action = $_POST['action'];

if($action == "get") {
    echo json_encode(Models::Get(), JSON_UNESCAPED_UNICODE);
} else if($action == "getById") {
    echo json_encode(Models::GetById($_POST['id']), JSON_UNESCAPED_UNICODE);
} else if($action == "add") {
    $model = new Models($_POST);
    $result = $model->Add();
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
} else if($action == "update") {
    $model = new Models($_POST);
    $result = $model->Update();
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
} else if($action == "delete") {
    $model = new Models($_POST);
    $result = $model->Delete();
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
?>