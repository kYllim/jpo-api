<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once '../config/Database.php';
    include_once '../class/Question.php';
    
    $database = new Question();
    $db = $database->getConnection();
    
    $item = new Question($db);
    
    $data = json_decode(file_get_contents("php://input"));
    
    $item->id_question = $data->id_question;
    var_dump($data->id_question);
    
    if($item->deleteQuestion()){
        echo json_encode("La question et ses réponses ont été supprimés");
        var_dump($item);
    } else{
        echo json_encode("La question n'a pas pu être supprimé");
    }
?>