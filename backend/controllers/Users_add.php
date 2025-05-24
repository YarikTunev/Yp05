<?php
require_once ("../classes/Users.php");
require_once ("../../connection.php");

$action = $_POST["action"];
if($action == "get"){
    echo json_encode(Users::Get(), JSON_UNESCAPED_UNICODE);
} else if($action == "getById"){
    echo json_encode(Users::GetById($_POST["id"]), JSON_UNESCAPED_UNICODE);
} else if ($action == "add") {
    $user = new Users($_POST);
    $params = $user->Add(); 
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
} 
else if ($action == "update") {
    $user = new Users($_POST);
    $params = $user->Update();
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
}
else if ($action == "delete"){
    $user = new Users($_POST);
    
    $params = $user->Delete(); 
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
}
?>