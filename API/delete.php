<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once '../config/Database.php';
    include_once '../class/Admin.php';
    include_once '../class/Users.php';
    include_once '../class/Answers.php';
    include_once '../class/Question.php';

    
    $database = new Database();
    $db = $database->getConnection();
    
    $data = json_decode(file_get_contents("php://input")); 

    if(isset($data->type)){
        if($data->type == "admin"){
            $admin = new Admin($db);
            $admin->id_admin = $data->id_admin;
            
            if($admin->deleteAdmin()){
                echo json_encode("L'administrateur a bien été supprimé.");
            } else{
                echo json_encode("Les données n'ont pu être supprimées.");
            }

        }elseif($data->type == "users"){
            $user = new Users($db);
            $user->id_user = $data->id_user;
            
            if($user->deleteUsers()){
                echo json_encode("L'utilisateur a bien été supprimé.");
            } else{
                echo json_encode("Les données n'ont pu être supprimées.");
            }
        }elseif($data->type == "answer"){
            // Delete Answer
            $itemAnswer = new Answers($db);
            $dataAnswer = json_decode(file_get_contents("php://input"));
            $itemAnswer->id_answer = $dataAnswer->id_answer;

            if ($itemAnswer->deleteAnswer()) {
                echo json_encode(array("message" => "Answer deleted."));
            } else {
                echo json_encode(array("message" => "Answer could not be deleted."));
            }
        }elseif($data->type == "question"){
            $itemQuestion = new Question($db);
            $dataQuestion = json_decode(file_get_contents("php://input"));
            $itemQuestion->id_question = $dataQuestion->id_question;

            if ($itemQuestion->deleteQuestion()) {
                echo json_encode(array("message" => "Question deleted."));
            } else {
                echo json_encode(array("message" => "Question could not be deleted."));
            }
        }else{
            echo json_encode(["message" => "Veuillez entrer le type de données attendues."]);
        }
    }
    
?>