<?php
    class Users{
        // Connection
        private $conn;
        // Table
        private $db_table = "users";
        // Columns
        public $id_user;
        public $first_name;
        public $name;
        public $mail;
        // Db connection
        public function __construct($db){
            $this->conn = $db;
        }
        // GET ALL
        public function getUsers(){
            $sqlQuery = "SELECT id_user, firstname, name, mail FROM " . $this->db_table . "";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }
        // CREATE
        public function createUsers(){
            $sqlQuery = "INSERT INTO
                        ". $this->db_table ."
                    SET
                        name = :name, 
                        mail = :mail, 
                        firstname = :firstname";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            //Sécurisation : 
                //htmlspecialchars() prévient contre l'éxécution involontaire de balise html
                //strip_tags() supprime les balises php et html 
            $this->name=htmlspecialchars(strip_tags($this->name));
            $this->mail=htmlspecialchars(strip_tags($this->mail));
            $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        
            //Liaison paramètre sql à variable php correspondante
            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":mail", $this->mail);
            $stmt->bindParam(":firstname", $this->firstname);
        
            if($stmt->execute()){
               return true;
            }
            return false;
        }
        // READ single
        public function getSingleUsers(){
            $sqlQuery = "SELECT
                            id_user, 
                            name, 
                            firstname, 
                            mail
                        FROM
                            " . $this->db_table . "
                        WHERE 
                            id_user = ?
                        LIMIT 0,1";
            
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->bindParam(1, $this->id_user);
            $stmt->execute();
            $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->name = $dataRow['name'];
            $this->mail = $dataRow['mail'];
            $this->firstname = $dataRow['firstname'];
            $this->id_user = $dataRow['id_user'];
        }
               
        // UPDATE
        public function updateUsers(){
            $sqlQuery = "UPDATE
                        ". $this->db_table ."
                    SET
                        name = :name, 
                        mail = :mail, 
                        firstname = :firstname 
                    WHERE 
                        id_user = :id_user";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->name=htmlspecialchars(strip_tags($this->name));
            $this->mail=htmlspecialchars(strip_tags($this->mail));
            $this->firstname=htmlspecialchars(strip_tags($this->firstname));
            $this->id_user=htmlspecialchars(strip_tags($this->id_user));
        
            // bind data
            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":mail", $this->mail);
            $stmt->bindParam(":firstname", $this->firstname);
            $stmt->bindParam(":id_user", $this->id_user);
        
            if($stmt->execute()){
               return true;
            }
            return false;
        }
        // DELETE
        function deleteUsers(){
            $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id_user = ?";
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->id_user=htmlspecialchars(strip_tags($this->id_user));
        
            $stmt->bindParam(1, $this->id_user);
        
            if($stmt->execute()){
                return true;
            }
            return false;
        }
    }
?>