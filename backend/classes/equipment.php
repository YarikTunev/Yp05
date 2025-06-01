<?php
class Equipment {
    public $id;
    public $name;
    public $photo;
    public $inventory_number;
    public $classroom_id;
    public $responsible_user_id;
    public $temp_responsible_user_id;
    public $cost;
    public $direction_id;
    public $status_id;
    public $model_id;
    public $comment;

    public function __construct($params)
    {
        if(isset($params["id"])) $this->id = $params["id"];
        if(isset($params["name"]))$this->name = $params['name'];
        if(isset($params["photo"]))$this->photo = $params['photo'] ?? ' ';
        if(isset($params["inventory_number"]))$this->inventory_number = $params['inventory_number'] ?? ' ';
        if(isset($params["classroom_id"]))$this->classroom_id = $params['classroom_id'] ?? ' ';
        if(isset($params["responsible_user_id"])) $this->responsible_user_id = $params["responsible_user_id"];
        if(isset($params["temp_responsible_user_id"])) $this->temp_responsible_user_id = $params["temp_responsible_user_id"];
        if(isset($params["cost"]))$this->cost = $params['cost'] ?? ' ';
        if(isset($params["direction_id"]))$this->direction_id = $params['direction_id'] ?? ' ';
        if(isset($params["status_id"]))$this->status_id = $params['status_id'] ?? ' ';
        if(isset($params["model_id"]))$this->model_id = $params['model_id'] ?? ' ';
        if(isset($params["comment"]))$this->comment = $params['comment'] ?? ' ';
    }

    public static function Get() {
        $connection = Connection::connect();
        $equipmentList = [];
        $query = $connection->query("SELECT * FROM `Equipment`");
        while ($read = $query->fetch_assoc()) {
            $equipment = new Equipment($read);
            $equipmentList[] = $equipment;
        }
        Connection::close($connection);
        return $equipmentList;
    }

    public static function GetById($id) {
        $connection = Connection::connect();
        $equipment = null;
        $query = $connection->prepare("SELECT * FROM `Equipment` WHERE `id` = ?");
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result();
        if ($result->num_rows > 0) {
            $read = $result->fetch_assoc();
            $equipment = new Equipment($read);
        }
        Connection::close($connection);
        return $equipment;
    }

    public function Add() {
        $connection = Connection::connect();

        echo json_encode($_POST);

        $query = $connection->prepare("INSERT INTO `Equipment` (`name`, `photo`, `inventory_number`, `classroom_id`,  `responsible_user_id`, `temp_responsible_user_id`, `cost`, `direction_id`, `status_id`, `model_id`, `comment`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $query->bind_param("ssiiiiiiiis",
            $this->name,
            $this->photo,
            $this->inventory_number,
            $this->classroom_id,
            $this->responsible_user_id,
            $this->temp_responsible_user_id,
            $this->cost,
            $this->direction_id,
            $this->status_id,
            $this->model_id,
            $this->comment
        );

        $success = $query->execute();

        Connection::close($connection);

        return $success;
    }

    public function Update()
    {
        $connection = Connection::connect();

        $query = $connection->prepare("UPDATE `Equipment` SET `name`=?, `photo`=?, `inventory_number`=?, `classroom_id`=?, `responsible_user_id`=?, `temp_responsible_user_id`=?, `cost`=?, `direction_id`=?, `status_id`=?, `model_id`=?, `comment`=? WHERE `id`=?");
        $query->bind_param("ssiiiiiiiisi",
            $this->name,
            $this->photo,
            $this->inventory_number,
            $this->classroom_id,
            $this->responsible_user_id,
            $this->temp_responsible_user_id,
            $this->cost,
            $this->direction_id,
            $this->status_id,
            $this->model_id,
            $this->comment,
            $this->id
        );
        $success = $query->execute();
        Connection::close($connection);
        return $success;
    }
    public function Delete() {
        $connection = Connection::connect();
        $query = $connection->prepare("DELETE FROM `Equipment` WHERE `id` = ?");
        $query->bind_param("i", $this->id);
        $success = $query->execute();
        Connection::close($connection);
        return $success;
    }
}
?>