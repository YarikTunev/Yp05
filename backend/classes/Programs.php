<?php
    class Program{
        public $id; 
        public $name;
        public $version;
        public $developer;

        public function __construct($params)
        {
            if(isset($params["id"])) $this->id = $params["id"];
            if(isset($params['name']) )$this->name = $params['name'];
            if(isset($params['version']) )$this->version = $params['version'] ?? ' ';
            if(isset($params['developer']) )$this->developer = $params['developer'] ?? ' ';
            
        }
        public static function Get()
        {
            $connection = Connection::connect();

            $Programs = array();

            $query = $connection->query("SELECT * FROM `programs`");
            while($read = $query->fetch_assoc()) {
                $Program = new Program($read);
                array_push($Programs, $Program);
            }

            Connection::close($connection);

            return $Programs;
        }
        public static function GetById($id)
        {
            $connection = Connection::connect();

            $Program = null;

            $query = $connection->query("SELECT * FROM `programs` WHERE `id` = {$id}");
            if($query->num_rows > 0) {
                $read = $query->fetch_assoc();
                $Program = new Program($read);
            }

            Connection::close($connection);

            return $Program;
        }
        public function Add()
        {
            $connection = Connection::connect();
            $query = $connection->prepare("INSERT INTO programs (name, version, developer)
                                        VALUES (?, ?, ?)");
            $query->bind_param("sss",
            $this->name,
            $this->version,
            $this->developer);
            $result = $query->execute();

            Connection::close($connection);

            return $result;
        }
        public function Update()
        {
            $connection = Connection::connect();

            $query = $connection->prepare("UPDATE programs SET name=?, version=?, developer=? WHERE id=?");
            $query->bind_param('sssi', $this->name, $this->version, $this->developer, $this->id);

            $result = $query->execute();

            Connection::close($connection);

            return $result;
        }
        public function Delete()
        {
            $connection = Connection::connect();

            $query = $connection->prepare("DELETE FROM programs WHERE id = ?");
            $query->bind_param("i", $this->id);
            $result = $query->execute();

            Connection::close($connection);

            return $result;
        }
    }
?>