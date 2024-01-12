<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
echo "test";

include_once '../config/Database.php';
include_once '../class/Answers.php';

$database = new Database();
$db = $database->getConnection();
$answers = new Answers($db);
// Récupérer l'ID de la question depuis la requête GET
$questionId = isset($_GET['question_id']) ? $_GET['question_id'] : null;

// Vérifier si l'ID de la question est défini
if ($questionId !== null) {
    // Récupérer la question
    $questionQuery = "SELECT id_question, question FROM questions WHERE id_question = ?";
    $stmtQuestion = $db->prepare($questionQuery);
    $stmtQuestion->bindParam(1, $questionId);
    $stmtQuestion->execute();
    $questionRow = $stmtQuestion->fetch(PDO::FETCH_ASSOC);

    if ($questionRow) {
        $question = $questionRow['question'];

        // Récupérer le nombre total de réponses à la question
        $totalAnswersQuery = "SELECT COUNT(*) as total FROM answers WHERE fk_question = ?";
        $stmtTotalAnswers = $db->prepare($totalAnswersQuery);
        $stmtTotalAnswers->bindParam(1, $questionId);
        $stmtTotalAnswers->execute();
        $totalRow = $stmtTotalAnswers->fetch(PDO::FETCH_ASSOC);
        $totalAnswers = $totalRow['total'];

        // Récupérer le nombre de réponses pour chaque option
        $optionsQuery = "SELECT answer, COUNT(*) as count FROM answers WHERE fk_question = ? GROUP BY answer";
        $stmtOptions = $db->prepare($optionsQuery);
        $stmtOptions->bindParam(1, $questionId);
        $stmtOptions->execute();
        $optionsData = $stmtOptions->fetchAll(PDO::FETCH_ASSOC);

        // Calculer le pourcentage de réponses pour chaque option
        $percentageData = array();
        foreach ($optionsData as $option) {
            $percentage = ($option['count'] / $totalAnswers) * 100;
            $percentageData[] = array(
                "answer" => $option['answer'],
                "percentage" => round($percentage, 2)
            );
        }

        // Créer un tableau avec les données
        $resultData = array(
            "question_id" => $questionRow['id_question'],
            "question" => $question,
            "answers" => $percentageData
        );

        http_response_code(200);
        echo json_encode($resultData);
    } else {
        http_response_code(404);
        echo json_encode("La question n'a pas pu être trouvée.");
    }
} else {
    http_response_code(400);
    echo json_encode("L'ID de la question n'est pas spécifié dans la requête.");
}
?>
