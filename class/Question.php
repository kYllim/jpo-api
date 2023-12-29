<?php
    class Question{
        // Connection
        private $conn;
        // Table
        private $db_table = "questions";
        // Columns
        public $id_question;
        public $question;
        
        // Db connection
        public function __construct($db){
            $this->conn = $db;
        }
        // GET ALL
        public function getQuestion(){
            $sqlQuery = "SELECT id_question, question FROM " . $this->db_table . "";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }
        // CREATE
        public function createQuestion(){
            $sqlQuery = "INSERT INTO
                        ". $this->db_table ."
                    SET
                        question = :question";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            // sanitize
            $this->question = htmlspecialchars(strip_tags($this->question));
        
            // bind data
            $stmt->bindParam(":question", $this->question);
        
            if($stmt->execute()){
                // Return the ID of the last inserted question
                return $this->conn->lastInsertId();
            }
            return false;
        }
        // READ single
        public function getSingleQuestion(){
            $sqlQuery = "SELECT
                            id_question, 
                            question 
                        FROM
                            ". $this->db_table ."
                        WHERE 
                            id_question = ?
                        LIMIT 0,1";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->bindParam(1, $this->id_question);
            $stmt->execute();
        
            // Check if the fetch was successful
            if ($stmt->rowCount() > 0) {
                $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->question = $dataRow['question'];
                return $dataRow;
            } else {
                return false;
            }
        }  
        // UPDATE
        public function updateQuestion(){
            $sqlQuery = "UPDATE
                            ". $this->db_table ."
                        SET
                            question = :question
                        WHERE 
                            id_question = :id_question";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->question=htmlspecialchars(strip_tags($this->question));
        
            // bind data
            // assignation
            $stmt->bindParam(":question", $this->question);
            $stmt->bindParam(":id_question", $this->id_question);
        
            if($stmt->execute()){
                return true;
            }
            return false;
        }
        // DELETE
        function deleteQuestion(){
            $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id_question = ?";
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->id_question=htmlspecialchars(strip_tags($this->id_question));
        
            $stmt->bindParam(1, $this->id_question);
        
            if($stmt->execute()){
                return true;
            }
            return false;
        }
    }
?>