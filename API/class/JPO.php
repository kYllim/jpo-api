<?php
class JPO {
    // Connection
    private $conn;
    // Table
    private $db_table = "jpo";
    // Columns
    public $id;
    public $date_jpo;
    public $start_time;
    public $end_time;

    // Db connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // GET ALL
    public function getJPO() {
        $sqlQuery = "SELECT id, date_jpo, start_time, end_time FROM " . $this->db_table;
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    // UPDATE
    public function updateJPO() {
        $sqlQuery = "UPDATE
                    " . $this->db_table . "
                    SET
                        date_jpo = :date_jpo, 
                        start_time = :start_time, 
                        end_time = :end_time
                    WHERE 
                        id = :id";

        $stmt = $this->conn->prepare($sqlQuery);

        $this->date_jpo = htmlspecialchars(strip_tags($this->date_jpo));
        $this->start_time = htmlspecialchars(strip_tags($this->start_time));
        $this->end_time = htmlspecialchars(strip_tags($this->end_time));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind data
        $stmt->bindParam(":date_jpo", $this->date_jpo);
        $stmt->bindParam(":start_time", $this->start_time);
        $stmt->bindParam(":end_time", $this->end_time);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}

?>