<?php
class Inventory {
    public $id;
    public $name;
    public $start_date;
    public $end_date;
    public $created_by;

    public function __construct($params)
    {
        if(isset($params["id"])) $this->id = $params["id"];
        $this->name = $params['name'] ?? ' ';
        $this->start_date = $params['start_date'] ?? ' ';
        $this->end_date = $params['end_date'] ?? ' ';
        $this->created_by = $params['created_by'] ?? ' ';
    }

    public static function Get()
    {
        $connection = Connection::connect();

        $inventoryList = array();

        $query = $connection->query("SELECT * FROM `Inventory`");
        while($read = $query->fetch_assoc()) {
            $inventory = new Inventory($read);
            array_push($inventoryList, $inventory);
        }

        Connection::close($connection);

        return $inventoryList;
    }

    public static function GetById($id)
    {
        $connection = Connection::connect();

        $inventory = null;

        $query = $connection->prepare("SELECT * FROM `Inventory` WHERE `id` = ?");
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result();

        if($result->num_rows > 0) {
            $read = $result->fetch_assoc();
            $inventory = new Inventory($read);
        }

        Connection::close($connection);

        return $inventory;
    }

    public function Add()
    {
        $connection = Connection::connect();

        $query = $connection->prepare("INSERT INTO `Inventory` (`name`, `start_date`, `end_date`, `created_by`) VALUES (?, ?, ?, ?)");
        $query->bind_param("sssi",
            $this->name,
            $this->start_date,
            $this->end_date,
            $this->created_by
        );

        $success = $query->execute();

        Connection::close($connection);

        return $success;
    }

    public function Update()
    {
        $connection = Connection::connect();

        $query = $connection->prepare("UPDATE `Inventory` SET `name`=?, `start_date`=?, `end_date`=?, `created_by`=? WHERE `id`=?");
        $query->bind_param("sssii",
            $this->name,
            $this->start_date,
            $this->end_date,
            $this->created_by,
            $this->id
        );

        $success = $query->execute();

        Connection::close($connection);

        return $success;
    }

    public function Delete()
    {
        $connection = Connection::connect();

        $query = $connection->prepare("DELETE FROM `Inventory` WHERE `id`=?");
        $query->bind_param("i", $this->id);

        $success = $query->execute();

        Connection::close($connection);

        return $success;
    }
}
?>
