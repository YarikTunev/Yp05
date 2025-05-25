<?php
    class Direction{
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

            $Directions = array();

            $query = $connection->query("SELECT * FROM `directions`");
            while($read = $query->fetch_assoc()) {
                $Direction = new Direction($read);
                array_push($Directions, $Direction);
            }

            Connection::close($connection);

            return $Directions;
        }
        public static function GetById($id)
        {
            $connection = Connection::connect();

            $Direction = null;

            $query = $connection->query("SELECT * FROM `directions` WHERE `id` = {$id}");
            if($query->num_rows > 0) {
                $read = $query->fetch_assoc();
                $Direction = new Direction($read);
            }

            Connection::close($connection);

            return $Direction;
        }
        public function Add()
        {
            $connection = Connection::connect();
            $query = $connection->prepare("INSERT INTO directions (name)
                                        VALUES (?)");
            $query->bind_param("s", $this->name);
            $result = $query->execute();

            Connection::close($connection);

            return $result;
        }
        public function Update()
        {
            $connection = Connection::connect();

            $query = $connection->prepare("UPDATE directions SET name=? WHERE id=?");
            $query->bind_param("si", $this->name, $this->id);

            $result = $query->execute();

            Connection::close($connection);

            return $result;
        }
        public function Delete()
        {
            $connection = Connection::connect();

            $query = $connection->prepare("DELETE FROM directions WHERE id = ?");
            $query->bind_param("i", $this->id);
            $result = $query->execute();

            Connection::close($connection);

            return $result;
        }
        

    }
?>