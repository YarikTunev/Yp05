<?php
class Equipment {
    public $id;
    public $name;
    public $photo_path;
    public $inventory_number;
    public $cost;
    public $direction;
    public $status;
    public $equipment_type;
    public $model;
    public $comment;
    public $created_at;
    public $updated_at;
    public $classroom_id;

    public function __construct($params)
    {
        if(isset($params["id"])) $this->id = $params["id"];
        if(isset($params["name"]))$this->name = $params['name'];
        if(isset($params["photo_path"]))$this->photo_path = $params['photo_path'] ?? ' ';
        if(isset($params["inventory_number"]))$this->inventory_number = $params['inventory_number'] ?? ' ';
        if(isset($params["cost"]))$this->cost = $params['cost'] ?? ' ';
        if(isset($params["direction"]))$this->direction = $params['direction'] ?? ' ';
        if(isset($params["status"]))$this->status = $params['status'] ?? ' ';
        if(isset($params["equipment_type"]))$this->equipment_type = $params['equipment_type'] ?? ' ';
        if(isset($params["model"]))$this->model = $params['model'] ?? ' ';
        if(isset($params["comment"]))$this->comment = $params['comment'] ?? ' ';
        if(isset($params["created_at"]))$this->created_at = $params['created_at'] ?? ' ';
        if(isset($params["updated_at"]))$this->updated_at = $params['updated_at'] ?? ' ';
        if(isset($params["classroom_id"]))$this->classroom_id = $params['classroom_id'] ?? ' ';
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

        $query = $connection->prepare("INSERT INTO `Equipment` (`name`, `photo_path`, `inventory_number`, `cost`, `direction`, `status`, `equipment_type`, `model`, `comment`, `created_at`, `updated_at`, `classroom_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $query->bind_param("ssiisssssssi",
            $this->name,
            $this->photo_path,
            $this->inventory_number,
            $this->cost,
            $this->direction,
            $this->status,
            $this->equipment_type,
            $this->model,
            $this->comment,
            $this->created_at,
            $this->updated_at,
            $this->classroom_id
        );

        $success = $query->execute();

        Connection::close($connection);

        return $success;
    }

    public function Update()
    {
        $connection = Connection::connect();

        $query = $connection->prepare("UPDATE `Equipment` SET `name`=?, `photo_path`=?, `inventory_number`=?, `cost`=?, `direction`=?, `status`=?, `equipment_type`=?, `model`=?, `comment`=?, `created_at`=?, `updated_at`=?, `classroom_id`=? WHERE `id`=?");
        $query->bind_param("ssiisssssssii",
            $this->name,
            $this->photo_path,
            $this->inventory_number,
            $this->cost,
            $this->direction,
            $this->status,
            $this->equipment_type,
            $this->model,
            $this->comment,
            $this->created_at,
            $this->updated_at,
            $this->classroom_id,
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
