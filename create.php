<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
    }
    
include_once '../config/Database.php';
include_once '../class/Answers.php';


$database = new Database();
$db = $database->getConnection();
$answer = new Answers($db);

// Récupérez les données JSON de la demande
$data = json_decode(file_get_contents("php://input"));

// Assurez-vous que les données nécessaires sont fournies
if (!empty($data->answer) && !empty($data->fk_question) && !empty($data->mail)) {
    // Affectez les valeurs des propriétés de l'objet $answer
    $answer->answer = $data->answer;
    $answer->fk_question = $data->fk_question;
    $answer->mail = $data->mail;

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

error_log(print_r($data, true));

?>
