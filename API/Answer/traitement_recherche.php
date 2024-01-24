<?php
ob_clean();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Inclure les fichiers nécessaires
include_once '../config/Database.php';
include_once '../class/Users.php';
include_once '../class/Answers.php';

// Initialiser la base de données
$database = new Database();
$db = $database->getConnection();

// Initialiser les objets Users et Answers
$users = new Users($db);
$answers = new Answers($db);

// Récupérer le paramètre de recherche depuis la requête GET
$mail = isset($_GET['mail']) ? $_GET['mail'] : null;

// Utiliser le paramètre pour récupérer l'utilisateur correspondant par e-mail
if ($mail && $users->getUserByMail($mail)) {
    $userMail = $users->mail;

    // Utiliser l'adresse e-mail de l'utilisateur pour récupérer les réponses associées
    $answersData = $answers->getAnswersByUserMail($userMail);

    $responseArray = array();
    while ($row = $answersData->fetch(PDO::FETCH_ASSOC)) {
        $responseArray[] = $row;
    }

    http_response_code(200);
    echo json_encode($responseArray);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Aucun utilisateur trouvé."));
}
?>
