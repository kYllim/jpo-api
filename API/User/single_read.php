<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
   
    include_once '../config/Database.php';
    include_once '../class/Users.php';

    $database = new Database();
    $db = $database->getConnection();
    $item = new Users($db);

    // Récupérer l'ID de l'user depuis la requête GET
    $item->id_user = isset($_GET['id_user']) ? $_GET['id_user'] : die();
  
    $item->getSingleUser(
        
        $item->mail != null){
        // create array
        $adm_arr = array(
            "id_user" =>  $item->id_user,
            "name" => $item->name,
            "firstname" => $item->firstname,
            "mail" => $item->mail
        );
      
        http_response_code(200);
        echo json_encode($adm_arr);
    }
      
    else{
        http_response_code(404);
        echo json_encode("L'user n'a pas pu être trouvé.");
    }
?>