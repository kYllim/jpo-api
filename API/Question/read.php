<?php
ob_clean(); // Clean the output buffer
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/Database.php';
include_once '../class/Question.php';

$database = new Database();
$db = $database->getConnection();
$items = new Question($db);
$stmt = $items->getQuestion();
$itemCount = $stmt->rowCount();

if ($itemCount > 0) {
    $questionArr = array();
    $questionArr["body"] = array();
    $questionArr["itemCount"] = $itemCount;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $e = array(
            "id_question" => $id_question,
            "question" => $question
        );
        array_push($questionArr["body"], $e);
    }

    echo json_encode($questionArr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No record found."));
}
?>
