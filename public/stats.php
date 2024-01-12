<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/Database.php';
include_once '../../class/Answers.php';

$database = new Database();
$db = $database->getConnection();
$answers = new Answers($db);

// Récupérer l'ID de la question depuis la requête GET
$questionId = isset($_GET['question_id']) ? $_GET['question_id'] : null;

// Vérifier si l'ID de la question est défini
if ($questionId !== null) {
    // ... (votre code pour récupérer les statistiques)

    // Créer un tableau avec les données
    $resultData = array(
        "question_id" => $questionRow['id_question'],
        "question" => $question,
        "answers" => $percentageData
    );

    http_response_code(200);
    echo json_encode($resultData);
} else {
    http_response_code(400);
    echo json_encode(array("message" => "L'ID de la question n'est pas spécifié dans la requête."));
}
?>
