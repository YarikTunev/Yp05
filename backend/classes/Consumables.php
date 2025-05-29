<?php
class Consumables {
    public $id;
    public $name;
    public $description;
    public $date_received;
    public $image;
    public $quantity;
    public $cost;
    public $responsible_user_id;
    public $temp_responsible_user_id;
    public $consumable_type_id;

    public function __construct($params) {
        if(isset($params['id'])) $this->id = $params['id'];
        if(isset($params['name'])) $this->name = $params['name'];
        if(isset($params['description'])) $this->description = $params['description'] ?? ' ';
        if(isset($params['date_received'])) $this->date_received = $params['date_received'];
        if(isset($params['image'])) $this->image = $params['image'] ?? ' ';
        if(isset($params['quantity'])) $this->quantity = $params['quantity'];
        if(isset($params['cost'])) $this->cost = $params['cost'];
        if(isset($params['responsible_user_id'])) $this->responsible_user_id = $params['responsible_user_id'] ?? ' ';
        if(isset($params['temp_responsible_user_id'])) $this->temp_responsible_user_id = $params['temp_responsible_user_id'] ?? ' ';
        if(isset($params['consumable_type_id'])) $this->consumable_type_id = $params['consumable_type_id'] ?? ' ';
    }

    public static function Get() {
        $connection = Connection::connect();
        $consumables = array();
        $query = $connection->query("SELECT * FROM `consumables`");
        while($row = $query->fetch_assoc()) {
            $consumable = new Consumables($row);
            array_push($consumables, $consumable);
        }
        Connection::close($connection);
        return $consumables;
    }

    public static function GetById($id) {
        $connection = Connection::connect();
        $consumable = null;
        $stmt = $connection->prepare("SELECT * FROM `consumables` WHERE `id` = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $consumable = new Consumables($row);
        }
        Connection::close($connection);
        return $consumable;
    }

    public function Add() {
        $connection = Connection::connect();
        $stmt = $connection->prepare("INSERT INTO `consumables` (name, description, date_received, image, quantity, cost, responsible_user_id, temp_responsible_user_id, consumable_type_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssbiiiii", $this->name, $this->description, $this->date_received, $this->image, $this->quantity, $this->cost, $this->responsible_user_id, $this->temp_responsible_user_id, $this->consumable_type_id);
        $stmt->send_long_data(3, $this->image);
        $result = $stmt->execute();
        Connection::close($connection);
        return $result;
    }

    public function Update() {
        $connection = Connection::connect();
        $stmt = $connection->prepare("UPDATE `consumables` SET name = ?, description = ?, date_received = ?, image = ?, quantity = ?, cost = ?, responsible_user_id = ?, temp_responsible_user_id = ?, consumable_type_id = ? WHERE id = ?");
        $stmt->bind_param("sssbiiiiii", $this->name, $this->description, $this->date_received, $this->image, $this->quantity, $this->cost, $this->responsible_user_id, $this->temp_responsible_user_id, $this->consumable_type_id, $this->id);
        $stmt->send_long_data(3, $this->image);
        $result = $stmt->execute();
        Connection::close($connection);
        return $result;
    }

    public function Delete() {
        $connection = Connection::connect();
        $stmt = $connection->prepare("DELETE FROM `consumables` WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $result = $stmt->execute();
        Connection::close($connection);
        return $result;
    }
}
?>