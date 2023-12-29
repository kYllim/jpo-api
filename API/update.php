<?php
// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// On vérifie la méthode
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // On inclut les fichiers de configuration et d'accès aux données
    include_once '../config/Database.php';
    include_once '../class/Admin.php';
    include_once '../class/Users.php';
    include_once '../class/Answers.php';
    include_once '../class/Question.php';


    // On instancie la base de données
    $database = new Database();
    $db = $database->getConnection();

    // On récupère les informations envoyées
    $data = json_decode(file_get_contents("php://input"));
    if(isset($data->type)){
        if($data->type == "admin"){
            $admin = new Admin($db);

            // Vérifier si les propriétés nécessaires sont présentes
            if (!empty($data->id_admin) && !empty($data->name) && !empty($data->mail) && !empty($data->firstname) && !empty($data->password)) {
                // On hydrate notre objet
                $admin->id_admin = $data->id_admin;
                $admin->name = $data->name;
                $admin->mail = $data->mail;
                $admin->firstname = $data->firstname;
                $admin->password = $data->password;

                // Vérifier si la mise à jour a réussi
                if ($admin->updateAdmin()) {
                    // Ici la modification a fonctionné
                    echo json_encode(["message" => "La modification a été effectuée"]);
                } else {
                    // Ici la modification n'a pas fonctionné
                    echo json_encode(["message" => "La modification n'a pas été effectuée"]);
                }
            } else {
                // Données incomplètes
                echo json_encode(["message" => "Les données fournies sont incomplètes"]);
            }
        }elseif($data->type == "users"){
            $user = new Users($db);

            // Vérifier si les propriétés nécessaires sont présentes
            if (!empty($data->id_user) && !empty($data->name) && !empty($data->mail) && !empty($data->firstname)) {
                // On hydrate notre objet
                $user->id_user = $data->id_user;
                $user->name = $data->name;
                $user->mail = $data->mail;
                $user->firstname = $data->firstname;

                // Vérifier si la mise à jour a réussi
                if ($user->updateUsers()) {
                    // Ici la modification a fonctionné
                    echo json_encode(["message" => "La modification a été effectuée"]);
                } else {
                    // Ici la modification n'a pas fonctionné
                    echo json_encode(["message" => "La modification n'a pas été effectuée"]);
                }
            } else {
                // Données incomplètes
                echo json_encode(["message" => "Les données fournies sont incomplètes"]);
            }
        }elseif($data->type == "answer"){
            $item = new Answers($db);
    
            $data = json_decode(file_get_contents("php://input"));
            
            $item->id_answer = $data->id_answer;
            
            if($item->updateAnswer()){
                echo json_encode("answer modified.");
            } else{
                echo json_encode("Data could not be modified");
            }
        }elseif($data->type == "question"){
            $item = new Question($db);
    
            $data = json_decode(file_get_contents("php://input"));
            
            $item->id_question = $data->id_question;
            $item->question = $data->question;

            if($item->updateQuestion()){
                echo json_encode("Question modified.");
            } else{
                echo json_encode("Data could not be modified");
            }

        }else{
                echo json_encode(["message" => "Veuillez entrer le type de données attendues."]);
            }
    }

} else {
    // Méthode non autorisée
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
