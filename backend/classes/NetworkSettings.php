<?php
    class NetworkSettings {
    public $id;
    public $equipment_id;
    public $ip_address;
    public $subnet_mask;
    public $gateway;
    public $dns_servers;

    public function __construct($params)
    {
        if(isset($params["id"])) $this->id = $params["id"];
        $this->equipment_id = $params['equipment_id'] ?? ' ';
        $this->ip_address = $params['ip_address'];
        $this->subnet_mask = $params['subnet_mask'] ?? ' ';
        $this->gateway = $params['gateway'] ?? ' ';
        $this->dns_servers = $params['dns_servers'] ?? ' ';
    }

    public static function Get()
    {
        $connection = Connection::connect();

        $networkSettingsList = array();

        $query = $connection->query("SELECT * FROM `network_settings`");
        while($read = $query->fetch_assoc()) {
            $networkSettings = new NetworkSettings($read);
            array_push($networkSettingsList, $networkSettings);
        }

        Connection::close($connection);

        return $networkSettingsList;
    }

    public static function GetById($id)
    {
        $connection = Connection::connect();

        $networkSettings = null;

        $query = $connection->query("SELECT * FROM `network_settings` WHERE `id` = {$id}");
        if($query->num_rows > 0) {
            $read = $query->fetch_assoc();
            $networkSettings = new NetworkSettings($read);
        }

        Connection::close($connection);

        return $networkSettings;
    }

    public function Add()
    {
        $connection = Connection::connect();

        $query = $connection->prepare("INSERT INTO `network_settings` (`equipment_id`, `ip_address`, `subnet_mask`, `gateway`, `dns_servers`) VALUES (?, ?, ?, ?, ?)");
        $query->bind_param("isiss",
            $this->equipment_id,
            $this->ip_address,
            $this->subnet_mask,
            $this->gateway,
            $this->dns_servers,
        );

        $result = $query->execute();

        Connection::close($connection);

        return $result;
    }

    public function Update()
    {
        $connection = Connection::connect();

        $query = $connection->prepare("UPDATE `network_settings` SET `equipment_id`=?, `ip_address`=?, `subnet_mask`=?, `gateway`=?, `dns_servers`=? WHERE `id`=?");
        $query->bind_param("isissi",
            $this->equipment_id,
            $this->ip_address,
            $this->subnet_mask,
            $this->gateway,
            $this->dns_servers,
            $this->id
        );

        $result = $query->execute();

        Connection::close($connection);

        return $result;
    }

    public function Delete()
    {
        $connection = Connection::connect();

        $query = $connection->prepare("DELETE FROM `network_settings` WHERE `id`=?");
        $query->bind_param("i", $this->id);

        $result = $query->execute();

        Connection::close($connection);

        return $result;
    }
}
?>
