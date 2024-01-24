<?php
ob_clean();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/Database.php';
include_once '../class/Answers.php';

$database = new Database();
$db = $database->getConnection();
$answers = new Answers($db);
$stmt = $answers->getAnswer();
$itemCount = $stmt->rowCount();

if ($itemCount > 0) {
    $answerArr = array();
    $answerArr["body"] = array();
    $answerArr["itemCount"] = $itemCount;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id_answer = $row['id_answer'];
        $answer = $row['answer'];
        $fk_question = $row['fk_question'];
        $mail = $row['mail'];

        $e = array(
            "id_answer" => $id_answer,
            "answer" => $answer,
            "fk_question" => $fk_question,
            "mail" => $mail
        );

        array_push($answerArr["body"], $e);
    }

    echo json_encode($answerArr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Aucun enregistrement trouvé."));
}
?>
