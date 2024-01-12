<?php
ob_clean(); // Nettoie le tampon de sortie
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/Database.php';
include_once '../class/Answers.php';

$database = new Database();
$db = $database->getConnection();
$items = new Answers($db);
$stmt = $items->getAnswer();
$itemCount = $stmt->rowCount();

if ($itemCount > 0) {
    $answerArr = array();
    $answerArr["body"] = array();
    $answerArr["itemCount"] = $itemCount;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id_answer = $row['id_answer'];
        $answer = $row['answer'];
        $fk_question = $row['fk_question'];
        $fk_user = array(
            "name" => $row['user_name'],
            "firstname" => $row['user_firstname'],
            "mail" => $row['user_mail']

        );

        $e = array(
            "id_answer" => $id_answer,
            "answer" => $answer,
            "fk_question" => $fk_question,
            "fk_user" => $fk_user
        );

        array_push($answerArr["body"], $e);
    }

    echo json_encode($answerArr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Aucun enregistrement trouvé."));
}



?>


