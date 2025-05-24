<?php
require_once ("../classes/сlassrooms.php");
require_once ("../../connection.php");

$action = $_POST["action"];
if($action == "get"){
    echo json_encode(Classroom::Get(), JSON_UNESCAPED_UNICODE);
} else if($action == "getById"){
    echo json_encode(Classroom::GetById($_POST["id"]), JSON_UNESCAPED_UNICODE);
} else if ($action == "add") {
    $classroom = new Classroom($_POST);
    $params = $classroom->Add();
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
} else if ($action == "update") {
    $classroom = new Classroom($_POST);
    $params = $classroom->Update();
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
} else if ($action == "delete"){
    $classroom = new Classroom($_POST);
    $params = $classroom->Delete();
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
}
?>
