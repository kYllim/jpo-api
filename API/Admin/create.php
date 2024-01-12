<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/Database.php';
include_once '../class/Admin.php';

$database = new Database();
$db = $database->getConnection();
$data = json_decode(file_get_contents("php://input"));


$item = new Admin($db);

// Valider les données d'entrée
if (empty($data->firstname) || empty($data->name) || empty($data->mail) || empty($data->password)) {
    http_response_code(400);
    echo json_encode(array("message" => "Tous les champs sont obligatoires."));
    exit();
}

// Vérification de l'adresse e-mail
if (!filter_var($data->mail, FILTER_VALIDATE_EMAIL) || !preg_match('/@univ-eiffel\.fr$/', $data->mail)) {
    http_response_code(400);
    echo json_encode(array("message" => "Vous ne faites pas parti de l'Université d'Eiffel."));
    exit();
}

// Set data
$item->firstname = $data->firstname;
$item->name = $data->name;
$item->mail = $data->mail;
$item->password = $data->password;

// Vérifier si l'adresse e-mail existe déjà
if ($item->mailExists($item->mail)) {
    http_response_code(400);
    echo json_encode(array("message" => "L'adresse e-mail existe déjà."));
    exit();
}

// Hachage du mot de passe
$item->password = password_hash($data->password, PASSWORD_BCRYPT, ['cost' => 12]);

// Tentative de création d'admin
if ($item->createAdmin()) {
    echo json_encode(array("message" => "L'admin a été créé."));
} else {
    http_response_code(500);
    echo json_encode(array("message" => "L'admin n'a pas pu être créé."));
}
?>
