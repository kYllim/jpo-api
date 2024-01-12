<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once '../config/Database.php';
    include_once '../class/Admin.php';
    
    $database = new Admin();
    $db = $database->getConnection();
    
    $item = new Admin($db);
    
    $data = json_decode(file_get_contents("php://input"));
    
    $item->id_admin = $data->id_admin;
    var_dump($data->id_admin);
    
    if($item->deleteAdmin()){
        echo json_encode("L'admin a été supprimé");
        var_dump($item);
    } else{
        echo json_encode("L'admin n'a pas pu être supprimé");
    }
?>