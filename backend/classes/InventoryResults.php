<?php
class InventoryResults {
    public $id;
    public $session_id;
    public $equipment_id;
    public $user_id;
    public $checked_by;
    public $check_date;
    public $status_id;
    public $comment;

    public function __construct($params)
    {
        if(isset($params["id"])) $this->id = $params["id"];
        $this->session_id = $params['session_id'] ?? ' ';
        $this->equipment_id = $params['equipment_id'] ?? ' ';
        $this->user_id = $params['user_id'] ?? ' ';
        $this->checked_by = $params['checked_by'] ?? ' ';
        $this->check_date = $params['check_date'] ?? ' ';
        $this->status_id = $params['status_id'] ?? ' ';
        $this->comment = $params['comment'] ?? ' ';
    }

    public static function Get()
    {
        $connection = Connection::connect();

        $inventoryResultsList = array();

        $query = $connection->query("SELECT * FROM `inventoryResults`");
        while($read = $query->fetch_assoc()) {
            $inventoryResults = new InventoryResults($read);
            array_push($inventoryResultsList, $inventoryResults);
        }

        Connection::close($connection);

        return $inventoryResultsList;
    }

    public static function GetById($id)
    {
        $connection = Connection::connect();

        $inventoryResults = null;

        $query = $connection->prepare("SELECT * FROM `inventoryResults` WHERE `id` = ?");
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result();

        if($result->num_rows > 0) {
            $read = $result->fetch_assoc();
            $inventoryResults = new InventoryResults($read);
        }

        Connection::close($connection);

        return $inventoryResults;
    }

    public function Add()
    {
        $connection = Connection::connect();

        $query = $connection->prepare("INSERT INTO `inventoryResults` (`session_id`, `equipment_id`, `user_id`, `checked_by`, `check_date`, `status_id`, `comment`) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $query->bind_param("iiiisis",
            $this->session_id,
            $this->equipment_id,
            $this->user_id,
            $this->checked_by,
            $this->check_date,
            $this->status_id,
            $this->comment
        );

        $success = $query->execute();

        Connection::close($connection);

        return $success;
    }

    public function Update()
    {
        $connection = Connection::connect();

        $query = $connection->prepare("UPDATE `inventoryResults` SET `session_id`=?, `equipment_id`=?, `user_id`=?, `checked_by`=?, `check_date`=?, `status_id`=?, `comment`=? WHERE `id`=?");
        $query->bind_param("iiiisisi",
            $this->session_id,
            $this->equipment_id,
            $this->user_id,
            $this->checked_by,
            $this->check_date,
            $this->status_id,
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

        $query = $connection->prepare("DELETE FROM `inventoryResults` WHERE `id`=?");
        $query->bind_param("i", $this->id);

        $success = $query->execute();

        Connection::close($connection);

        return $success;
    }
}
?>
