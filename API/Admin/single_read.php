<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/Database.php';
include_once '../class/Admin.php';

$database = new Database();
$db = $database->getConnection();
$item = new Admin($db);

// Récupérer l'ID de l'admin depuis la requête GET
$item->id_admin = isset($_GET['id_admin']) ? $_GET['id_admin'] : die();

// Utiliser la méthode getSingleAdmin
$item->getSingleAdmin();

// Vérifier si l'admin a été trouvé
if ($item->id_admin != null) {
    // Créer un tableau associatif
    $adm_arr = array(
        "id_admin" => $item->id_admin,
        "name" => $item->name,
        "firstname" => $item->firstname,
        "mail" => $item->mail
    );

    http_response_code(200);
    echo json_encode($adm_arr);
} else {
    http_response_code(404);
    echo json_encode("L'admin n'a pas pu être trouvé.");
}
?>
