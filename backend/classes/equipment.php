<?php
class Equipment {
    public $id;
    public $name;
    public $photo_path;
    public $inventory_number;
    public $cost;
    public $direction_id;
    public $status_id;
    public $model_id;
    public $comment;
    public $room_id;
    public $responsible_user_id;
    public $temp_responsible_user_id;

    public function __construct($params)
    {
        if(isset($params["id"])) $this->id = $params["id"];
        if(isset($params["name"]))$this->name = $params['name'];
        if(isset($params["photo_path"]))$this->photo_path = $params['photo_path'] ?? ' ';
        if(isset($params["inventory_number"]))$this->inventory_number = $params['inventory_number'] ?? ' ';
        if(isset($params["cost"]))$this->cost = $params['cost'] ?? ' ';
        if(isset($params["direction_id"]))$this->direction_id = $params['direction_id'] ?? ' ';
        if(isset($params["status_id"]))$this->status_id = $params['status_id'] ?? ' ';
        if(isset($params["model_id"]))$this->model_id = $params['model_id'] ?? ' ';
        if(isset($params["comment"]))$this->comment = $params['comment'] ?? ' ';
        if(isset($params["responsible_user_id"])) $this->responsible_user_id = $params["responsible_user_id"];
        if(isset($params["temp_responsible_user_id"])) $this->temp_responsible_user_id = $params["temp_responsible_user_id"];
        if(isset($params["room_id"]))$this->room_id = $params['room_id'] ?? ' ';
    }

    public static function Get()
    {
        $connection = Connection::connect();

        $equipmentList = array();

        $query = $connection->query("SELECT * FROM `Equipment`");
        while($read = $query->fetch_assoc()) {
            $equipment = new Equipment($read);
            array_push($equipmentList, $equipment);
        }

        Connection::close($connection);

        return $equipmentList;
    }

    public static function GetById($id)
    {
        $connection = Connection::connect();

        $equipment = null;

        $query = $connection->prepare("SELECT * FROM `Equipment` WHERE `id` = ?");
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result();

        if($result->num_rows > 0) {
            $read = $result->fetch_assoc();
            $equipment = new Equipment($read);
        }

        Connection::close($connection);

        return $equipment;
    }

    public function Add()
    {
        $connection = Connection::connect();

        echo json_encode($_POST);

        $query = $connection->prepare("INSERT INTO `Equipment` (`name`, `photo_path`, `inventory_number`, `cost`, `direction_id`, `status_id`, `model_id`, `comment`, `room_id`, `responsible_user_id`, `temp_responsible_user_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $query->bind_param("ssiiiiisiii",
            $this->name,
            $this->photo_path,
            $this->inventory_number,
            $this->cost,
            $this->direction_id,
            $this->status_id,
            $this->model_id,
            $this->comment,
            $this->room_id,
            $this->responsible_user_id,
            $this->temp_responsible_user_id
        );

        $success = $query->execute();

        Connection::close($connection);

        return $success;
    }

    public function Update()
    {
        $connection = Connection::connect();

        $query = $connection->prepare("UPDATE `Equipment` SET `name`=?, `photo_path`=?, `inventory_number`=?, `cost`=?, `direction_id`=?, `status_id`=?, `model_id`=?, `comment`=?, `room_id`=?, `responsible_user_id`=?, `temp_responsible_user_id`=? WHERE `id`=?");
        $query->bind_param("ssiiiiisiiii",
            $this->name,
            $this->photo_path,
            $this->inventory_number,
            $this->cost,
            $this->direction_id,
            $this->status_id,
            $this->model_id,
            $this->comment,
            $this->room_id,
            $this->responsible_user_id,
            $this->temp_responsible_user_id,
            $this->id
        );

        $success = $query->execute();

        Connection::close($connection);

        return $success;
    }

    public function Delete()
    {
        $connection = Connection::connect();

        $query = $connection->prepare("DELETE FROM `Equipment` WHERE `id`=?");
        $query->bind_param("i", $this->id);

        $success = $query->execute();

        Connection::close($connection);

        return $success;
    }
}
?>
