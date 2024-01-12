<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/Database.php';
include_once '../class/Question.php';

$database = new Database();
$db = $database->getConnection();
$item = new Question($db);
$data = json_decode(file_get_contents("php://input"));

// Valider les données d'entrée
if (empty($data->question)) {
    http_response_code(400);
    echo json_encode(array("message" => "Tous les champs sont obligatoires."));
    exit();
}

// Set data
$item->question = $data->question;

// Tentative de création d'admin
if ($item->createQuestion()) {
    echo json_encode(array("message" => "La question a été créée."));
} else {
    http_response_code(500);
    echo json_encode(array("message" => "La question n'a pas pu être créée."));
}
?>
