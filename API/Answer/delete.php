<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once '../config/Database.php';
    include_once '../class/Answers.php';
    
    $database = new Answers();
    $db = $database->getConnection();
    
    $item = new Answers($db);
    
    $data = json_decode(file_get_contents("php://input"));
    
    $item->id_answer = $data->id_answer;
    var_dump($data->id_answer);
    
    if($item->deleteAnswer()){
        echo json_encode("L'answer a été supprimé");
        var_dump($item);
    } else{
        echo json_encode("L'answer n'a pas pu être supprimé");
    }
?>