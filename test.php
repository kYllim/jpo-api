<?php
// Initialiser cURL
$ch = curl_init();

// Définir l'URL de la requête
curl_setopt($ch, CURLOPT_URL, 'https://bilou.alwaysdata.net/API/Answer/read.php');

// Spécifier la méthode HTTP (GET dans cet exemple)
curl_setopt($ch, CURLOPT_HTTPGET, true);

// Exécuter la requête et récupérer la réponse
$response = curl_exec($ch);

// Vérifier s'il y a des erreurs
if (curl_errno($ch)) {
    echo json_encode(['error' => 'Erreur cURL : ' . curl_error($ch)]);
} else {
    // Convertir la réponse JSON en tableau PHP
    $data = json_decode($response, true);

    // Vérifier si la conversion JSON a réussi
    if ($data !== null) {
        // Retourner les données au format JSON
        echo json_encode($data);
    } else {
        echo json_encode(['error' => 'Erreur lors de la conversion JSON.']);
    }
}

// Fermer la session cURL
curl_close($ch);
?>
