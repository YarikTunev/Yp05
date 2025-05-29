<?php
class Models {
    public $id;
    public $name;
    public $equipment_type_id;

    public function __construct($params) {
        if(isset($params['id'])) $this->id = $params['id'];
        if(isset($params['name'])) $this->name = $params['name'];
        if(isset($params['equipment_type_id'])) $this->equipment_type_id = $params['equipment_type_id'];
    }

    public static function Get() {
        $connection = Connection::connect();
        $models = array();
        $query = $connection->query("SELECT * FROM `models`");
        while($row = $query->fetch_assoc()) {
            $model = new Models($row);
            array_push($models, $model);
        }
        Connection::close($connection);
        return $models;
    }

    public static function GetById($id) {
        $connection = Connection::connect();
        $model = null;
        $query = $connection->query("SELECT * FROM `models` WHERE `id` = {$id}");
        if($query->num_rows > 0) {
            $row = $query->fetch_assoc();
            $model = new Models($row);
        }
        Connection::close($connection);
        return $model;
    }

    public function Add() {
        $connection = Connection::connect();
        $stmt = $connection->prepare("INSERT INTO `models` (name, equipment_type_id) VALUES (?, ?)");
        $stmt->bind_param("si", $this->name, $this->equipment_type_id);
        $result = $stmt->execute();
        Connection::close($connection);
        return $result;
    }

    public function Update() {
        $connection = Connection::connect();
        $stmt = $connection->prepare("UPDATE `models` SET name = ?, equipment_type_id = ? WHERE id = ?");
        $stmt->bind_param("sii", $this->name, $this->equipment_type_id, $this->id);
        $result = $stmt->execute();
        Connection::close($connection);
        return $result;
    }

    public function Delete() {
        $connection = Connection::connect();
        $stmt = $connection->prepare("DELETE FROM `models` WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $result = $stmt->execute();
        Connection::close($connection);
        return $result;
    }
}
?>