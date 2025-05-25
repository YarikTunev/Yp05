<?php
require_once ("../classes/Status.php");
require_once ("../../connection.php");

$action = $_POST["action"];
if($action == "get"){
    echo json_encode(Status::Get(), JSON_UNESCAPED_UNICODE);
} else if($action == "getById"){
    echo json_encode(Status::GetById($_POST["id"]), JSON_UNESCAPED_UNICODE);
} else if ($action == "add") {
    $status = new Status($_POST);
    $params = $status->Add(); 
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
} 
else if ($action == "update") {
    $status = new Status($_POST);
    $params = $status->Update();
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
}
else if ($action == "delete"){
    $status = new Status($_POST);
    
    $params = $status->Delete(); 
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
}
?>