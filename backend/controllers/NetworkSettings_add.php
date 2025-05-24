<?php
require_once ("../classes/NetworkSettings.php");
require_once ("../../connection.php");

$action = $_POST["action"];
if($action == "get"){
    echo json_encode(NetworkSettings::Get(), JSON_UNESCAPED_UNICODE);
} else if($action == "getById"){
    echo json_encode(NetworkSettings::GetById($_POST["id"]), JSON_UNESCAPED_UNICODE);
} else if ($action == "add") {
    $networkSettings = new NetworkSettings($_POST);
    $params = $networkSettings->Add();
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
}
else if ($action == "update") {
    $networkSettings = new NetworkSettings($_POST);
    $params = $networkSettings->Update();
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
}
else if ($action == "delete"){
    $networkSettings = new NetworkSettings($_POST);

    $params = $networkSettings->Delete();
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
}
?>
