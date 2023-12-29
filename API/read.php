<?php
// Définir les en-têtes pour autoriser les requêtes depuis n'importe quelle origine
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// On inclut les fichiers nécessaires
include_once '../config/Database.php';
include_once '../class/Admin.php';
include_once '../class/Users.php';
include_once '../class/Answers.php';
include_once '../class/Question.php';

// Instancier la base de données
$database = new Database();
$db = $database->getConnection();

// On récupère les informations envoyées
$data = json_decode(file_get_contents("php://input"));
if(isset($data->type)){
    if(isset($data->single) && $data->single == "yes" ){
        if($data->type == "admin"){
            $itemsAdmin = new Admin($db);
            $itemsAdmin->id_admin = $data->id_admin;
            $itemsAdmin->getSingleAdmin();
        
            $adminArr = array(
                "id_admin" => $itemsAdmin->id_admin,
                "name" => $itemsAdmin->name,
                "firstname" => $itemsAdmin->firstname,
                "mail" => $itemsAdmin->mail
            );
        
            echo json_encode($adminArr);
            
        }elseif($data->type == "user"){
            $itemsUsers = new Users($db);
            $itemsUsers->id_user = $data->id_user;
            $itemsUsers->getSingleUsers();
        
            $usersArr = array(
                "id_user" => $itemsUsers->id_user,
                "name" => $itemsUsers->name,
                "firstname" => $itemsUsers->firstname,
                "mail" => $itemsUsers->mail
            );
        
            echo json_encode($usersArr);
        }elseif ($data->type == "answer") {
            $itemsAnswers = new Answers($db);
            $itemsAnswers->id_answer = $data->id_answer;
            $itemsAnswers->getSingleAnswer();
        
            $answerArr = array(
                "id_answer" => $itemsAnswers->id_answer,
                "answer" => $itemsAnswers->answer,
                "question" => $itemsAnswers->fk_question,
                "user" => array(
                    "id_user" => $itemsAnswers->fk_user['id_user'],
                    "name" => $itemsAnswers->fk_user['name'],
                    "firstname" => $itemsAnswers->fk_user['firstname'],
                )
            );
        
            echo json_encode($answerArr);

        }elseif($data->type == "question"){
            $itemsQuestion = new Question($db);
            $itemsQuestion->id_question = $data->id_question;
            $itemsQuestion->getSingleQuestion();
        
            $questionArr = array(
                "id_question" => $itemsQuestion->id_question,
                "question" => $itemsQuestion->question,
            );
        
            echo json_encode($questionArr);
        }

    }else{
        if($data->type == "admin"){
            // Instancier la classe Admin
            $admin = new Admin($db);
    
            //if($data->quantity == "single"){
                    
            //}
            //else{
                // Récupérer tous les administrateurs
                $stmt = $admin->getAdmin();
    
                // Obtenir le nombre d'administrateurs
                $adminCount = $stmt->rowCount();
    
                // Afficher le nombre d'administrateurs (uniquement pour débogage)
                echo json_encode($adminCount);
    
                // Vérifier s'il y a des administrateurs
                if ($adminCount > 0) {
                    // Créer un tableau pour stocker les données des administrateurs
                    $adminArr = array();
                    $adminArr["body"] = array();
                    $adminArr["adminCount"] = $adminCount;
    
                    // Parcourir les résultats et extraire les données
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
    
                        // Créer un tableau associatif pour chaque administrateur
                        $adminItem = array(
                            "id_admin" => $id_admin,
                            "name" => $name,
                            "mail" => $mail,
                            "firstname" => $firstname,
                            "password" => $password,
                        );
    
                        // Ajouter l'administrateur au tableau principal
                        array_push($adminArr["body"], $adminItem);
                    }
    
                    // Afficher les données des administrateurs au format JSON
                    echo json_encode($adminArr);
                } else {
                    // Aucun administrateur trouvé
                    echo json_encode(array("message" => "No record found."));
                }
        }elseif($data->type == "user"){
            $user = new Users($db);
            // Récupérer tous les administrateurs
            $stmt = $user->getUsers();
    
            // Obtenir le nombre d'administrateurs
            $userCount = $stmt->rowCount();
        
            // Afficher le nombre d'administrateurs (uniquement pour débogage)
            echo json_encode($userCount);
        
            // Vérifier s'il y a des administrateurs
            if ($userCount > 0) {
                // Créer un tableau pour stocker les données des administrateurs
                $userArr = array();
                $userArr["body"] = array();
                $userArr["userCount"] = $userCount;
        
                // Parcourir les résultats et extraire les données
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
        
                    // Créer un tableau associatif pour chaque administrateur
                    $userItem = array(
                        "id_user" => $id_user,
                        "name" => $name,
                        "mail" => $mail,
                        "firstname" => $firstname,
                    );
        
                    // Ajouter l'administrateur au tableau principal
                    array_push($userArr["body"], $userItem);
                }
        
                // Afficher les données des administrateurs au format JSON
                echo json_encode($userArr);
            } else {
                // Aucun administrateur trouvé
                echo json_encode(array("message" => "No record found."));
            }
        
        }elseif ($data->type == "answer") {
            $itemsAnswers = new Answers($db);
            $stmtAnswers = $itemsAnswers->getAnswer();
        
            if ($stmtAnswers) {
                $itemCountAnswers = $stmtAnswers->rowCount();
        
                if ($itemCountAnswers > 0) {
                    $answerArr = array();
                    $answerArr["body"] = array();
                    $answerArr["itemCount"] = $itemCountAnswers;
        
                    while ($row = $stmtAnswers->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $e = array(
                            "id_answer" => $id_answer,
                            "answer" => $answer,
                            "question" => $question,
                            "user_name" => $user_name, 
                            "user_firstname" => $user_firstname 
                        );
                        array_push($answerArr["body"], $e);
                    }
        
                    echo json_encode($answerArr);
                } else {
                    http_response_code(404);
                    echo json_encode(array("message" => "No answer records found."));
                }
            } else {
                http_response_code(500);
                echo json_encode(array("message" => "Internal Server Error"));
            }
        }elseif($data->type == "question"){
            $itemsQuestion = new Question($db);
            $stmtQuestion = $itemsQuestion->getQuestion();
            $itemCountQuestion = $stmtQuestion->rowCount();
    
            if ($itemCountQuestion > 0) {
                $QuestionArr = array();
                $QuestionArr["body"] = array();
                $QuestionArr["itemCount"] = $itemCountQuestion;
    
                while ($row = $stmtQuestion->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $e = array(
                        "id_question" => $id_question,
                        "question" => $question,
                    );
                    array_push($QuestionArr["body"], $e);
                }
    
                echo json_encode($QuestionArr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "No Question records found."));
            }
        }
    }

}



?>
