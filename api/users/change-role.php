<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");
require_once '../../config/db.php';
require_once '../util/response.php';


$response = new Response();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    try {
        
        $data = array();
        $id = $_GET['id'];
        $req_data = json_decode(file_get_contents("php://input"));

        $stmt = $db_con->prepare("UPDATE users SET role = :role WHERE id = :id");
        $stmt->bindParam("id", $id);
        $stmt->bindParam("role", $req_data->role);


        if ($stmt->execute()) {
            $data["id"] = $id;
            $data["role"] = $req_data->role;

            $response->success($data, 'success');
        } else {
            $response->error('error can not update!');
        }
        
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
} else {
    $response->error('Error Method!!');
    die();
}
?>