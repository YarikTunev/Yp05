<?php
require_once ("../classes/Directions.php");
require_once ("../../connection.php");

$action = $_POST["action"];
if($action == "get"){
    echo json_encode(Direction::Get(), JSON_UNESCAPED_UNICODE);
} else if($action == "getById"){
    echo json_encode(Direction::GetById($_POST["id"]), JSON_UNESCAPED_UNICODE);
} else if ($action == "add") {
    $Direction = new Direction($_POST);
    $params = $Direction->Add(); 
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
} 
else if ($action == "update") {
    $Direction = new Direction($_POST);
    $params = $Direction->Update();
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
}
else if ($action == "delete"){
    $Direction = new Direction($_POST);
    
    $params = $Direction->Delete(); 
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
}
?>