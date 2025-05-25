<?php
    class Connection {
        public static function connect() {
            $hostname = "localhost";
            $username = "root";
            $password = "";
            $bdname = "stydu";
            $connection = mysqli_connect($hostname,  $username, $password, $bdname);
            if (!$connection) {
                die("Ошибка подключения: " . mysqli_connect_error());
            }
            return $connection;
        }

        public static function close($connection) {
            $connection->close();
        }
    }
?>