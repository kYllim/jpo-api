<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/Database.php';
include_once '../class/Answers.php';

$database = new Database();
$db = $database->getConnection();
$answer = new Answers($db);

// Récupérez les données JSON de la demande
$data = json_decode(file_get_contents("php://input"));

// Assurez-vous que les données nécessaires sont fournies
if (!empty($data->answer) && !empty($data->fk_question) && !empty($data->fk_user)) {
    // Affectez les valeurs des propriétés de l'objet $answer
    $answer->answer = $data->answer;
    $answer->fk_question = $data->fk_question;
    $answer->fk_user = $data->fk_user;

    // Essayez de créer la réponse
    if ($answer->createAnswer()) {
        http_response_code(201); // Réponse de création réussie
        echo json_encode(array("message" => "La réponse a été créée avec succès."));
    } else {
        http_response_code(503); // Service indisponible
        echo json_encode(array("message" => "Impossible de créer la réponse."));
    }
} else {
    http_response_code(400); // Mauvaise requête
    echo json_encode(array("message" => "Impossible de créer la réponse. Données incomplètes."));
}
?>
