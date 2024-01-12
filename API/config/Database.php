<?php
class Database {
    private $host = "mysql-bilou.alwaysdata.net";
    private $database_name = "bilou_jpoiut";
    private $username = "bilou";
    private $password = "Meaux'vit";
    public $conn;

    public function getConnection(){
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Database could not be connected: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>