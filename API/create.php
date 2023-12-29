<?php
// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Vérification si les données ne sont pas vides
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // On inclut les fichiers nécessaires
    include_once '../config/Database.php';
    include_once '../class/Admin.php';
    include_once '../class/Users.php';
    include_once '../class/Answers.php';
    include_once '../class/Question.php';

    // On instancie la base de données
    $database = new Database();
    $db = $database->getConnection();

    //On récupère les informations envoyées
    $data = json_decode(file_get_contents("php://input"));

    //On vérifie si le type de donnée existe
    if(isset($data->type)){
        // On vérifie la cible de l'entrée
        if($data->type == "admin"){
            // Vérification si les données admin ne sont pas vides
            if (!empty($data->name) && !empty($data->mail) && !empty($data->firstname) && !empty($data->password)) {
                // On instancie l'administrateur
                $admin = new Admin($db);
    
                // On rentre les informations dans l'objet
                $admin->name = $data->name;
                $admin->mail = $data->mail;
                $admin->firstname = $data->firstname;
                $admin->password = $data->password;
    
                // Appel de la méthode pour créer l'administrateur
                if ($admin->createAdmin()) {
                    // Ici, la création a fonctionné pour l'administrateur
                    echo json_encode(["message" => "L'ajout de l'administrateur a été effectué"]);
                } else {
                    // Ici, la création n'a pas fonctionné pour l'administrateur
                    echo json_encode(["message" => "L'ajout de l'administrateur n'a pas été effectué"]);
                }
            } else {
                // Si des données sont manquantes pour l'administrateur
                echo json_encode(["message" => "Toutes les données nécessaires pour l'administrateur ne sont pas fournies"]);
            }
    
        }elseif($data->type == "users"){
            // Vérification si les données users ne sont pas vides
            if (!empty($data->name) && !empty($data->mail) && !empty($data->firstname)) {
                // On instancie l'utilisateur
                $user = new Users($db);
    
                // On rentre les informations dans l'objet
                $user->name = $data->name;
                $user->mail = $data->mail;
                $user->firstname = $data->firstname;
    
                // Appel de la méthode pour créer l'utilisateur
                if ($user->createUsers()) {
                    // Ici, la création a fonctionné pour l'utilisateur
                    echo json_encode(["message" => "L'ajout de l'utilisateur a été effectué"]);
                } else {
                    // Ici, la création n'a pas fonctionné pour l'utilisateur
                    echo json_encode(["message" => "L'ajout de l'utilisateur n'a pas été effectué"]);
                }
            } else {
                // Si des données sont manquantes pour l'utilisateur
                echo json_encode(["message" => "Toutes les données nécessaires pour l'utilisateur ne sont pas fournies"]);
            }
        }elseif($data->type == "answer"){
            // Create Answer
            $itemAnswer = new Answers($db);
            $dataAnswer = json_decode(file_get_contents("php://input"));
    
            $itemAnswer->answer = $dataAnswer->answer;
            $itemAnswer->fk_question = $dataAnswer->fk_question;
            $itemAnswer->fk_user = $dataAnswer->fk_user;
    
            // Then call createAnswer
            if ($itemAnswer->createAnswer()) {
                echo json_encode(array("message" => "Answer created successfully."));
            } else {
                echo json_encode(array("message" => "Unable to create answer."));
            }
        }elseif($data->type == "question"){
            // Create Question
            $itemQuestion = new Question($db);
    
            // Check if data is present and not null
            $dataQuestion = json_decode(file_get_contents("php://input"));
            if ($dataQuestion && isset($dataQuestion->question)) {
                $itemQuestion->question = $dataQuestion->question;
    
                // Create the question and get the ID
                $createdQuestionId = $itemQuestion->createQuestion();
    
                if ($createdQuestionId) {
                    // Fetch the created question using the ID
                    $createdQuestion = $itemQuestion->getSingleQuestion($createdQuestionId);
    
                    // Check if the question was fetched successfully
                    if ($createdQuestion) {
                        echo json_encode(array("message" => "Question created.", "question" => $createdQuestion));
                    } else {
                        echo json_encode(array("message" => "Question created"));
                    }
                } else {
                    echo json_encode(array("message" => "Question could not be created."));
                }
            } else {
                // Handle the case when the question is not present in the input data
                echo json_encode(array("message" => "Invalid input data. 'question' property not found."));
            }
        }else{
            echo json_encode(["message" => "Veuillez entrer le type de données attendues."]);
        }
    }




    
} else {
    // On gère l'erreur si la méthode n'est pas autorisée
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
?>