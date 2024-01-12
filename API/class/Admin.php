<?php
    class Admin{
        // Connection
        private $conn;
        // Table
        private $db_table = "admin";
        // Columns
        public $id_admin;
        public $firstname;
        public $name;
        public $mail;
        public $password;
        // Db connection
        public function __construct($db){
            $this->conn = $db;
        }
        // GET ALL
        public function getAdmin(){
            $sqlQuery = "SELECT id_admin, firstname, name, mail, password FROM " . $this->db_table . "";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }
        // CREATE
        public function createAdmin(){
            $sqlQuery = "INSERT INTO
                        ". $this->db_table ."
                    SET
                        name = :name, 
                        mail = :mail, 
                        firstname = :firstname, 
                        password = :password";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            //Sécurisation : 
                //htmlspecialchars() prévient contre l'éxécution involontaire de balise html
                //strip_tags() supprime les balises php et html 
            $this->name=htmlspecialchars(strip_tags($this->name));
            $this->mail=htmlspecialchars(strip_tags($this->mail));
            $this->firstname=htmlspecialchars(strip_tags($this->firstname));
            $this->password=htmlspecialchars(strip_tags($this->password));
        
            //Liaison paramètre sql à variable php correspondante
            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":mail", $this->mail);
            $stmt->bindParam(":firstname", $this->firstname);
            $stmt->bindParam(":password", $this->password);
        
            if($stmt->execute()){
               return true;
            }
            return false;
        }

        public function mailExists($mail){
            $query = "SELECT COUNT(*) as count FROM " . $this->db_table . " WHERE mail = :mail";
            $stmt = $this->conn->prepare($query);
            $mail = htmlspecialchars(strip_tags($mail));
            $stmt->bindParam(":mail", $mail);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        }
        
        // READ single
        public function getSingleAdmin(){
            $sqlQuery = "SELECT
                        id_admin, 
                        name, 
                        firstname, 
                        mail
                    FROM
                        " . $this->db_table . "
                    WHERE 
                    id_admin = ?
                    LIMIT 0,1";

            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->bindParam(1, $this->id_admin);
            $stmt->execute();
            $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->name = $dataRow['name'];
            $this->mail = $dataRow['mail'];
            $this->firstname = $dataRow['firstname'];
            $this->id_admin = $dataRow['id_admin'];
        }        
        // UPDATE
        public function updateAdmin(){
            $sqlQuery = "UPDATE
                        ". $this->db_table ."
                    SET
                        name = :name, 
                        mail = :mail, 
                        firstname = :firstname, 
                        password = :password
                    WHERE 
                        id_admin = :id_admin";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->name=htmlspecialchars(strip_tags($this->name));
            $this->mail=htmlspecialchars(strip_tags($this->mail));
            $this->firstname=htmlspecialchars(strip_tags($this->firstname));
            $this->password=htmlspecialchars(strip_tags($this->password));
            $this->id_admin=htmlspecialchars(strip_tags($this->id_admin));
        
            // bind data
            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":mail", $this->mail);
            $stmt->bindParam(":firstname", $this->firstname);
            $stmt->bindParam(":password", $this->password);
            $stmt->bindParam(":id_admin", $this->id_admin);
        
            if($stmt->execute()){
               return true;
            }
            return false;
        }
        // DELETE
        function deleteAdmin(){
            $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id_admin = ?";
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->id_admin=htmlspecialchars(strip_tags($this->id_admin));
        
            $stmt->bindParam(1, $this->id_admin);
        
            if($stmt->execute()){
                return true;
            }
            return false;
        }
    }
?>