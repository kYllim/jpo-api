<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/Database.php';
include_once '../class/Users.php';

$database = new Database();
$db = $database->getConnection();
$item = new Users($db);
$data = json_decode(file_get_contents("php://input"));

// Valider les données d'entrée
if (empty($data->firstname) || empty($data->name) || empty($data->mail)) {
    http_response_code(400);
    echo json_encode(array("message" => "Tous les champs sont obligatoires."));
    exit();
}

// Set data
$item->firstname = $data->firstname;
$item->name = $data->name;
$item->mail = $data->mail;

// Vérifier si l'adresse e-mail existe déjà
if ($item->mailExists($item->mail)) {
    http_response_code(400);
    echo json_encode(array("message" => "L'adresse e-mail existe déjà."));
    exit();
}

// Tentative de création d'User
if ($item->createUsers()) {
    echo json_encode(array("message" => "L'User a été créé."));
} else {
    http_response_code(500);
    echo json_encode(array("message" => "L'User n'a pas pu être créé."));
}
?>
