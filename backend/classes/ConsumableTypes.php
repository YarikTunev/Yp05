<?php
// Класс для работы с типами расходников
class ConsumableTypes {
    public $id;
    public $name;

    public function __construct($data) {
        if (isset($data['id'])) $this->id = $data['id'];
        if (isset($data['name'])) $this->name = $data['name'];
    }

    // Получить все типы расходников
    public static function Get() {
        $c = Connection::connect();
        $types = [];
        $result = $c->query("SELECT * FROM `consumable_types`");
        while($row = $result->fetch_assoc()) {
            $types[] = new ConsumableTypes($row);
        }
        Connection::close($c);
        return $types;
    }

    // Получить тип по ID
    public static function GetById($id) {
        $c = Connection::connect();
        $stmt = $c->prepare("SELECT * FROM `consumable_types` WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $type = $res->fetch_assoc() ? new ConsumableTypes($res->fetch_assoc()) : null;
        Connection::close($c);
        return $type;
    }

    // Добавить новый тип
    public function Add() {
        $c = Connection::connect();
        $stmt = $c->prepare("INSERT INTO `consumable_types` (name) VALUES (?)");
        $stmt->bind_param("s", $this->name);
        $res = $stmt->execute();
        Connection::close($c);
        return $res;
    }

    // Обновить тип
    public function Update() {
        $c = Connection::connect();
        $stmt = $c->prepare("UPDATE `consumable_types` SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $this->name, $this->id);
        $res = $stmt->execute();
        Connection::close($c);
        return $res;
    }

    // Удалить тип
    public function Delete() {
        $c = Connection::connect();
        $stmt = $c->prepare("DELETE FROM `consumable_types` WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $res = $stmt->execute();
        Connection::close($c);
        return $res;
    }
}