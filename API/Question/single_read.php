<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/Database.php';
include_once '../class/Question.php';

$database = new Database();
$db = $database->getConnection();
$item = new Question($db);

// Récupérer l'ID de la question depuis la requête GET
$item->id_question = isset($_GET['id_question']) ? $_GET['id_question'] : die();

// Utiliser la méthode getSingleQuestion
$item->getSingleQuestion();

if ($item->question != null) {
    // Créer un tableau associatif
    $qst_arr = array(
        "id_question" => $item->id_question,
        "question" => $item->question
    );

    http_response_code(200);
    echo json_encode($qst_arr);
} else {
    http_response_code(404);
    echo json_encode("La question n'a pas pu être trouvée.");
}
?>
