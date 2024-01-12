<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/Database.php';
include_once '../class/Answers.php';

$database = new Database();
$db = $database->getConnection();
$item = new Answers($db);

// Récupérer l'ID de l'answer depuis la requête GET
$item->id_answer = isset($_GET['id_answer']) ? $_GET['id_answer'] : null;

// Vérifier si l'ID de l'answer est défini
if ($item->id_answer !== null) {
    $item->getSingleAnswer();

    if ($item->answer != null) {
        // Créer un tableau avec les données de l'answer
        $asw_arr = array(
            "id_answer" =>  $item->id_answer,
            "answer" => $item->answer,
            "fk_question" => $item->fk_question,
            "fk_user" => $item->fk_user
        );

        http_response_code(200);
        echo json_encode($asw_arr);
    } else {
        http_response_code(404);
        echo json_encode("La réponse n'a pas pu être trouvée.");
    }
} else {
    http_response_code(400);
    echo json_encode("L'ID de l'answer n'est pas spécifié dans la requête.");
}

?>