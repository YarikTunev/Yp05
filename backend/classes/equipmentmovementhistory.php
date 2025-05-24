<?php
class EquipmentMovementHistory {
    public $id;
    public $equipment_id;
    public $classroom_id;
    public $responsible_user_id;
    public $temp_responsible_user_id;
    public $movement_date;
    public $comment;

    public function __construct($params)
    {
        if(isset($params["id"])) $this->id = $params["id"];
        $this->equipment_id = $params['equipment_id'] ?? ' ';
        $this->classroom_id = $params['classroom_id'] ?? ' ';
        $this->responsible_user_id = $params['responsible_user_id'] ?? ' ';
        $this->temp_responsible_user_id = $params['temp_responsible_user_id'] ?? ' ';
        $this->movement_date = $params['movement_date'] ?? ' ';
        $this->comment = $params['comment'] ?? ' ';
    }

    public static function Get()
    {
        $connection = Connection::connect();

        $equipmentMovementHistorys = array();

        $query = $connection->query("SELECT * FROM `EquipmentMovementHistory`");
        while($read = $query->fetch_assoc()) {
            $equipmentMovementHistory = new EquipmentMovementHistory($read);
            array_push($equipmentMovementHistorys, $equipmentMovementHistory);
        }

        Connection::close($connection);

        return $equipmentMovementHistorys;
    }

    public static function GetById($id)
    {
        $connection = Connection::connect();

        $equipmentMovementHistory = null;

        $query = $connection->prepare("SELECT * FROM `EquipmentMovementHistory` WHERE `id` = ?");
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result();

        if($result->num_rows > 0) {
            $read = $result->fetch_assoc();
            $equipmentMovementHistory = new EquipmentMovementHistory($read);
        }

        Connection::close($connection);

        return $equipmentMovementHistory;
    }

    public function Add()
    {
        $connection = Connection::connect();

        $query = $connection->prepare("INSERT INTO `EquipmentMovementHistory` (`equipment_id`, `classroom_id`, `responsible_user_id`, `temp_responsible_user_id`, `movement_date`, `comment`) VALUES (?, ?, ?, ?, ?, ?)");
        $query->bind_param("iiisss",
            $this->equipment_id,
            $this->classroom_id,
            $this->responsible_user_id,
            $this->temp_responsible_user_id,
            $this->movement_date,
            $this->comment
        );

        $success = $query->execute();

        Connection::close($connection);

        return $success;
    }

    public function Update()
    {
        $connection = Connection::connect();

        $query = $connection->prepare("UPDATE `EquipmentMovementHistory` SET `equipment_id`=?, `classroom_id`=?, `responsible_user_id`=?, `temp_responsible_user_id`=?, `movement_date`=?, `comment`=? WHERE `id`=?");
        $query->bind_param("iiisssi",
            $this->equipment_id,
            $this->classroom_id,
            $this->responsible_user_id,
            $this->temp_responsible_user_id,
            $this->movement_date,
            $this->comment,
            $this->id
        );

        $success = $query->execute();

        Connection::close($connection);

        return $success;
    }

    public function Delete()
    {
        $connection = Connection::connect();

        $query = $connection->prepare("DELETE FROM `EquipmentMovementHistory` WHERE `id`=?");
        $query->bind_param("i", $this->id);

        $success = $query->execute();

        Connection::close($connection);

        return $success;
    }
}
?>
