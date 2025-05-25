<?php
require_once ("../classes/Programs.php");
require_once ("../../connection.php");

$action = $_POST["action"];
if($action == "get"){
    echo json_encode(Program::Get(), JSON_UNESCAPED_UNICODE);
} else if($action == "getById"){
    echo json_encode(Program::GetById($_POST["id"]), JSON_UNESCAPED_UNICODE);
} else if ($action == "add") {
    $Program = new Program($_POST);
    $params = $Program->Add(); 
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
} 
else if ($action == "update") {
    $Program = new Program($_POST);
    $params = $Program->Update();
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
}
else if ($action == "delete"){
    $Program = new Program($_POST);
    
    $params = $Program->Delete(); 
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
}
?>