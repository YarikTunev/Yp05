<?php
class InventoryResults {
    public $id;
    public $inventory_id;
    public $equipment_id;
    public $checked_by;
    public $check_date;
    public $status;
    public $comment;

    public function __construct($params)
    {
        if(isset($params["id"])) $this->id = $params["id"];
        $this->inventory_id = $params['inventory_id'] ?? ' ';
        $this->equipment_id = $params['equipment_id'] ?? ' ';
        $this->checked_by = $params['checked_by'] ?? ' ';
        $this->check_date = $params['check_date'] ?? ' ';
        $this->status = $params['status'] ?? ' ';
        $this->comment = $params['comment'] ?? ' ';
    }

    public static function Get()
    {
        $connection = Connection::connect();

        $inventoryResultsList = array();

        $query = $connection->query("SELECT * FROM `InventoryResults`");
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

        $query = $connection->prepare("SELECT * FROM `InventoryResults` WHERE `id` = ?");
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

        $query = $connection->prepare("INSERT INTO `InventoryResults` (`inventory_id`, `equipment_id`, `checked_by`, `check_date`, `status`, `comment`) VALUES (?, ?, ?, ?, ?, ?)");
        $query->bind_param("iissss",
            $this->inventory_id,
            $this->equipment_id,
            $this->checked_by,
            $this->check_date,
            $this->status,
            $this->comment
        );

        $success = $query->execute();

        Connection::close($connection);

        return $success;
    }

    public function Update()
    {
        $connection = Connection::connect();

        $query = $connection->prepare("UPDATE `InventoryResults` SET `inventory_id`=?, `equipment_id`=?, `checked_by`=?, `check_date`=?, `status`=?, `comment`=? WHERE `id`=?");
        $query->bind_param("iissssi",
            $this->inventory_id,
            $this->equipment_id,
            $this->checked_by,
            $this->check_date,
            $this->status,
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

        $query = $connection->prepare("DELETE FROM `InventoryResults` WHERE `id`=?");
        $query->bind_param("i", $this->id);

        $success = $query->execute();

        Connection::close($connection);

        return $success;
    }
}
?>
