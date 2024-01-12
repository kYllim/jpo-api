<?php
ob_clean(); // Nettoie le tampon de sortie
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Inclut les fichiers nécessaires pour la connexion à la base de données et la manipulation des utilisateurs
include_once '../config/Database.php';
include_once '../class/Users.php';

// Connexion BDD
$database = new Database();
$db = $database->getConnection();

$items = new Users($db); // Création d'un objet Users
$stmt = $items->getUsers(); // Récupéreration des utilisateurs depuis bdd avec la méthode getUsers()
$itemCount = $stmt->rowCount();// rowCount() obtient le nombre d'enregistrements récupérés

// Vérifie s'il y a des enregistrements d'utilisateurs
if ($itemCount > 0) {
    // Initialise un tableau pour stocker les utilisateurs et leurs informations
    $userArr = array();
    $userArr["body"] = array();
    $userArr["itemCount"] = $itemCount; 

    // Boucle à travers chaque ligne de résultat de la requête
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        // Crée un tableau associatif avec les informations de l'utilisateur
        $e = array(
            "id_user" => $id_user,
            "firstname" => $firstname,
            "name" => $name,
            "mail" => $mail
        );

        // Ajoute le tableau associatif au corps des données
        array_push($userArr["body"], $e);
    }

    // Convertit le tableau en format JSON et l'affiche
    echo json_encode($userArr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No record found."));
}
?>
