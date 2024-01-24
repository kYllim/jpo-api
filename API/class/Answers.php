<?php
class Answers {
    private $conn;
    private $db_table = "answers";

    public $id_answer;
    public $answer;
    public $fk_question;
    public $mail;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAnswer() {
        $sqlQuery = "SELECT a.id_answer, a.answer, q.question as fk_question, a.mail
                     FROM " . $this->db_table . " a
                     LEFT JOIN questions q ON a.fk_question = q.id_question";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    public function createAnswer() {
        $sqlQuery = "INSERT INTO " . $this->db_table . "
                        SET
                            answer = :answer,
                            fk_question = :fk_question,
                            mail = :mail";

        $stmt = $this->conn->prepare($sqlQuery);

        $this->answer = htmlspecialchars(strip_tags($this->answer));
        $this->fk_question = htmlspecialchars(strip_tags($this->fk_question));
        $this->mail = htmlspecialchars(strip_tags($this->mail));

        $stmt->bindParam(":answer", $this->answer);
        $stmt->bindParam(":fk_question", $this->fk_question);
        $stmt->bindParam(":mail", $this->mail);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getSingleAnswer() {
        $sqlQuery = "SELECT a.id_answer, a.answer, q.question, a.mail
                    FROM " . $this->db_table . " a
                    LEFT JOIN questions q ON a.fk_question = q.id_question
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
            $this->mail = $dataRow['mail'];
            return true;
        } else {
            throw new Exception("Réponse introuvable.");
        }
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

    public function getAnswersByUserMail($userMail) {
        $query = "SELECT a.id_answer, a.answer, q.question as fk_question, a.mail
                    FROM " . $this->db_table . " a
                    LEFT JOIN questions q ON a.fk_question = q.id_question
                    WHERE mail = :mail";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':mail', $userMail);
        $stmt->execute();
        return $stmt;
    }

}
?>
