<?php
class ConsumableCharacteristics {
    public $id;
    public $consumable_id;
    public $characteristic_name;

    public function __construct($p) {
        if(isset($p['id'])) $this->id = $p['id'];
        if(isset($p['consumable_id'])) $this->consumable_id = $p['consumable_id'];
        if(isset($p['characteristic_name'])) $this->characteristic_name = $p['characteristic_name'];
    }

    public static function Get() {
        $c = Connection::connect();
        $list = [];
        $q = $c->query("SELECT * FROM `consumable_characteristics`");
        while($r = $q->fetch_assoc()) {
            $list[] = new ConsumableCharacteristics($r);
        }
        Connection::close($c);
        return $list;
    }

    public static function GetById($id) {
        $c = Connection::connect();
        $stmt = $c->prepare("SELECT * FROM `consumable_characteristics` WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $o = null;
        if($row = $res->fetch_assoc()) {
            $o = new ConsumableCharacteristics($row);
        }
        Connection::close($c);
        return $o;
    }

    public function Add() {
        $c = Connection::connect();
        $stmt = $c->prepare("INSERT INTO `consumable_characteristics` (characteristic_name, consumable_id) VALUES (?, ?)");
        $stmt->bind_param("si", $this->characteristic_name, $this->consumable_id);
        $r = $stmt->execute();
        Connection::close($c);
        return $r;
    }

    public function Update() {
        $c = Connection::connect();
        $stmt = $c->prepare("UPDATE `consumable_characteristics` SET characteristic_name = ?, consumable_id = ? WHERE id = ?");
        $stmt->bind_param("sii", $this->characteristic_name, $this->consumable_id, $this->id);
        $r = $stmt->execute();
        Connection::close($c);
        return $r;
    }

    public function Delete() {
        $c = Connection::connect();
        $stmt = $c->prepare("DELETE FROM `consumable_characteristics` WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $r = $stmt->execute();
        Connection::close($c);
        return $r;
    }
}
?>