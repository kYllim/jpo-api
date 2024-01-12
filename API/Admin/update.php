<?php
// Autoriser les requêtes depuis n'importe quelle origine
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Inclure les fichiers nécessaires
include_once '../config/Database.php';
include_once '../class/Admin.php';

// Instancier la base de données et la classe Admin
$database = new Database();
$db = $database->getConnection();
$item = new Admin($db);

// Obtenir les données du corps de la requête au format JSON
$data = json_decode(file_get_contents("php://input"));

// Vérifier si les données nécessaires sont présentes
if (
    !empty($data->id_admin) && !empty($data->firstname) && !empty($data->name) && !empty($data->mail) && !empty($data->password)
) {
    // Assigner les valeurs aux propriétés de l'objet Admin
    $item->id_admin = $data->id_admin;
    $item->firstname = $data->firstname;
    $item->name = $data->name;
    $item->mail = $data->mail;

    // Hasher le mot de passe avant de l'assigner à la propriété
    $item->password = password_hash($data->password, PASSWORD_BCRYPT, ['cost' => 12]);

    // Mettre à jour les données de l'administrateur
    if ($item->updateAdmin()) {
        http_response_code(200); // OK
        echo json_encode(array("message" => "Les données de l'admin ont été mises à jour."));
    } else {
        http_response_code(500); // Erreur interne du serveur
        echo json_encode(array("message" => "Les données n'ont pas pu être mises à jour."));
    }
} else {
    http_response_code(400); // Requête incorrecte
    echo json_encode(array("message" => "Données incomplètes. Veuillez fournir toutes les informations requises."));
}
?>
