<?php
    class Status{
        public $id; 
        public $name; 

        public function __construct($params)
        {
            if(isset($params["id"])) $this->id = $params["id"];
            if(isset($params['name']) )$this->name = $params['name'];
            
        }
        public static function Get()
        {
            $connection = Connection::connect();

            $statuses = array();

            $query = $connection->query("SELECT * FROM `statuses`");
            while($read = $query->fetch_assoc()) {
                $status = new Status($read);
                array_push($statuses, $status);
            }

            Connection::close($connection);

            return $statuses;
        }
        public static function GetById($id)
        {
            $connection = Connection::connect();

            $status = null;

            $query = $connection->query("SELECT * FROM `statuses` WHERE `id` = {$id}");
            if($query->num_rows > 0) {
                $read = $query->fetch_assoc();
                $status = new Status($read);
            }

            Connection::close($connection);

            return $status;
        }
        public function Add()
        {
            $connection = Connection::connect();
            $query = $connection->prepare("INSERT INTO statuses (name)
                                        VALUES (?)");
            $query->bind_param("s", $this->name);
            $result = $query->execute();

            Connection::close($connection);

            return $result;
        }
        public function Update()
        {
            $connection = Connection::connect();

            $query = $connection->prepare("UPDATE statuses SET name=? WHERE id=?");
            $query->bind_param("si", $this->name, $this->id);

            $result = $query->execute();

            Connection::close($connection);

            return $result;
        }
        public function Delete()
        {
            $connection = Connection::connect();

            $query = $connection->prepare("DELETE FROM statuses WHERE id = ?");
            $query->bind_param("i", $this->id);
            $result = $query->execute();

            Connection::close($connection);

            return $result;
        }
        

    }
?>