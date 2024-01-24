<?php
    class Users{
        // Connection
        private $conn;
        // Table
        private $db_table = "users";
        // Columns
        public $id_user;
        public $firstname;
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

        public function getUserByMail($mail) {
            // Requête SQL pour récupérer l'utilisateur par e-mail
            $query = "SELECT * FROM " . $this->db_table . " WHERE mail = :mail";
    
            // Préparation de la requête
            $stmt = $this->conn->prepare($query);
    
            // Nettoyer les données
            $this->mail = htmlspecialchars(strip_tags($mail));
    
            // Lier les valeurs
            $stmt->bindParam(':mail', $this->mail);
    
            // Exécution de la requête
            $stmt->execute();
    
            // Récupération de la ligne correspondante
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Mise à jour des propriétés de l'objet
            if ($row) {
                $this->id_user = $row['id_user'];
                $this->name = $row['name'];
                $this->firstname = $row['firstname'];
                $this->mail = $row['mail'];
                // ... Ajoutez d'autres propriétés si nécessaire
                return true;
            }
    
            return false; // Aucun utilisateur trouvé
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