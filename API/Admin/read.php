<?php
ob_clean(); // Clean the output buffer
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/Database.php';
include_once '../class/Admin.php';

$database = new Database();
$db = $database->getConnection();
$items = new Admin($db);
$stmt = $items->getAdmin();
$itemCount = $stmt->rowCount();

if ($itemCount > 0) {
    $adminArr = array();
    $adminArr["body"] = array();
    $adminArr["itemCount"] = $itemCount;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $e = array(
            "id_admin" => $id_admin,
            "firstname" => $firstname,
            "name" => $name,
            "mail" => $mail
        );
        array_push($adminArr["body"], $e);
    }

    echo json_encode($adminArr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No record found."));
}
?>
