<?php
class Answers {
    private $conn;
    private $db_table = "answers";
    
    public $id_answer;
    public $answer;
    public $fk_question;
    public $fk_user;
    
    public function __construct($db){
        $this->conn = $db;
    }

    public function getAnswer() {
        $sqlQuery = "SELECT a.id_answer, a.answer, q.question as fk_question, u.name as user_name, u.firstname as user_firstname,u.mail as user_mail
                     FROM " . $this->db_table . " a
                     LEFT JOIN questions q ON a.fk_question = q.id_question
                     LEFT JOIN users u ON a.fk_user = u.id_user";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }
    
    

    
    public function createAnswer() {
        // Requête SQL pour insérer une nouvelle réponse avec les clés étrangères
        $sqlQuery = "INSERT INTO " . $this->db_table . "
                        SET
                            answer = :answer,
                            fk_question = :fk_question,
                            fk_user = :fk_user";
    
        // Préparation de la requête
        $stmt = $this->conn->prepare($sqlQuery);
    
        // Nettoyage et liaison des données
        $this->answer = htmlspecialchars(strip_tags($this->answer));
        $this->fk_question = htmlspecialchars(strip_tags($this->fk_question));
        $this->fk_user = htmlspecialchars(strip_tags($this->fk_user));
    
        $stmt->bindParam(":answer", $this->answer);
        $stmt->bindParam(":fk_question", $this->fk_question);
        $stmt->bindParam(":fk_user", $this->fk_user);
    
        // Exécution de la requête et retour du résultat
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function getSingleAnswer() {
        $sqlQuery = "SELECT a.id_answer, a.answer, q.question, u.name as user_name, u.firstname as user_firstname, u.id_user as user_id
                    FROM " . $this->db_table . " a
                    LEFT JOIN questions q ON a.fk_question = q.id_question
                    LEFT JOIN users u ON a.fk_user = u.id_user
                    WHERE a.id_answer = ?
                    LIMIT 1";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(1, $this->id_answer);
        $stmt->execute();

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($dataRow) {
            $this->id_answer = $dataRow['id_answer'];
            $this->answer = $dataRow['answer'];
            $this->fk_question = $dataRow['question'];
            $this->fk_user = array(
                "id_user" => $dataRow['user_id'],
                "name" => $dataRow['user_name'],
                "firstname" => $dataRow['user_firstname'],
            );
            return true; // Indique que la réponse a été trouvée
        } else {
            throw new Exception("Réponse introuvable."); // Lancer une exception si la réponse n'est pas trouvée
        }
    }
    
    
    
    
    

    public function updateAnswer() {
        $sqlQuery = "UPDATE " . $this->db_table . "
                    SET
                        answer = :answer,
                        fk_question = :fk_question,
                        fk_user = :fk_user
                    WHERE
                        id_answer = :id_answer";

        $stmt = $this->conn->prepare($sqlQuery);

        $this->answer = htmlspecialchars(strip_tags($this->answer));
        $this->fk_question = htmlspecialchars(strip_tags($this->fk_question));
        $this->fk_user = htmlspecialchars(strip_tags($this->fk_user));
        $this->id_answer = htmlspecialchars(strip_tags($this->id_answer));

        $stmt->bindParam(":answer", $this->answer);
        $stmt->bindParam(":fk_question", $this->fk_question);
        $stmt->bindParam(":fk_user", $this->fk_user);
        $stmt->bindParam(":id_answer", $this->id_answer);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function deleteAnswer() {
        $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id_answer = ?";
        $stmt = $this->conn->prepare($sqlQuery);
        $this->id_answer = htmlspecialchars(strip_tags($this->id_answer));
        $stmt->bindParam(1, $this->id_answer);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function answerExists($answer){
        $query = "SELECT COUNT(*) as count FROM " . $this->db_table . " WHERE answer = :answer";
        $stmt = $this->conn->prepare($query);
        $answer = htmlspecialchars(strip_tags($answer));
        $stmt->bindParam(":answer", $answer);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
}
?>