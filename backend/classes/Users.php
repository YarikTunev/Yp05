<?php
    class Users{
        public $id; 
        public $login; 
        public $password;
        public $role;
        public $email;
        public $last_name;
        public $first_name;
        public $middle_name;
        public $phone;
        public $address;

        public function __construct($params)
        {
            if(isset($params["id"])) $this->id = $params["id"];
            if(isset($params['login']) )$this->login = $params['login'];
            if(isset($params['password']) )$this->password = $params['password'];
            if(isset($params['role']) )$this->role = $params['role'];
            if(isset($params['email']) )$this->email = $params['email'] ?? ' ';
            if(isset($params['last_name']) )$this->last_name = $params['last_name'] ?? ' ';
            if(isset($params['first_name']) )$this->first_name = $params['first_name'] ?? ' ';
            if(isset($params['middle_name']) )$this->middle_name = $params['middle_name'] ?? ' ';
            if(isset($params['phone']) )$this->phone = $params['phone'] ?? ' ';
            if(isset($params['address']) )$this->address = $params['address'] ?? ' ';
            
        }
        public static function Get()
        {
            $connection = Connection::connect();

            $Userss = array();

            $query = $connection->query("SELECT * FROM `Users`");
            while($read = $query->fetch_assoc()) {
                $Users = new Users($read);
                array_push($Userss, $Users);
            }

            Connection::close($connection);

            return $Userss;
        }
        public static function GetById($id)
        {
            $connection = Connection::connect();

            $User = null;

            $query = $connection->query("SELECT * FROM `Users` WHERE `id` = {$id}");
            if($query->num_rows > 0) {
                $read = $query->fetch_assoc();
                $User = new Users($read);
            }

            Connection::close($connection);

            return $User;
        }
        public function Add()
        {
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
            $connection = Connection::connect();
            $query = $connection->prepare("INSERT INTO users (login, password, role, email, last_name, first_name, middle_name, phone, address)
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $query->bind_param("sssssssss", $this->login, $hashedPassword, $this->role, $this->email, $this->last_name, $this->first_name, $this->middle_name, $this->phone, $this->address);
            $result = $query->execute();

            Connection::close($connection);

            return $result;
        }
        public function Update()
        {
            $connection = Connection::connect();

            $query = $connection->prepare("UPDATE users SET login=?, password=?, role=?, email=?, last_name=?, first_name=?, middle_name=?, phone=?, address=? WHERE id=?");
            $query->bind_param("sssssssssi", $this->login, $this->password, $this->role, $this->email, $this->last_name, $this->first_name, $this->middle_name, $this->phone, $this->address, $this->id);

            $result = $query->execute();

            Connection::close($connection);

            return $result;
        }
        public function Delete()
        {
            $connection = Connection::connect();

            $query = $connection->prepare("DELETE FROM users WHERE id = ?");
            $query->bind_param("i", $this->id);
            $result = $query->execute();

            Connection::close($connection);

            return $result;
        }
        public static function GetByLogin($login) {
            $connection = Connection::connect();
            $query = $connection->prepare("SELECT * FROM `Users` WHERE `login` = ?");
            $query->bind_param("s", $login);
            $query->execute();
            $result = $query->get_result();

            if ($result->num_rows > 0) {
                $read = $result->fetch_assoc();
                return new Users($read);
            }

            Connection::close($connection);
            return null;
        }
    }
?>