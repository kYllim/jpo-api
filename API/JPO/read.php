<?php
// Entêtes requises pour autoriser les requêtes depuis n'importe quelle origine
ob_clean(); // Clean the output buffer
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Vérifie que la méthode utilisée est correcte (GET dans ce cas)

    // Inclut les fichiers de configuration et d'accès aux données
    include_once '../config/Database.php';
    include_once '../class/JPO.php';

    // Instancie la base de données
    $database = new Database();
    $db = $database->getConnection();

    // Instancie l'objet JPO
    $jpo = new JPO($db);

    // Récupère les données des JPOs
    $stmt = $jpo->getJPO();

    // Vérifie s'il y a au moins une JPO
    if ($stmt->rowCount() > 0) {
        // Initialise un tableau associatif pour stocker les JPOs
        $jpos = [];
        
        // Parcourt les résultats de la requête
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            // Crée un tableau représentant une JPO
            $jpoItem = [
                "id" => $id,
                "date_jpo" => $date_jpo,
                "start_time" => $start_time,
                "end_time" => $end_time
                // Ajoute d'autres champs si nécessaire
            ];

            // Ajoute la JPO au tableau
            $jpos[] = $jpoItem;
        }

        // Envoie le code réponse 200 OK avec les données encodées en JSON
        http_response_code(200);
        echo json_encode(["data" => $jpos]);
    } else {
        // Envoie le code réponse 404 Not Found avec un message d'erreur
        http_response_code(404);
        echo json_encode(["error" => "Aucune JPO trouvée."]);
    }

?>
