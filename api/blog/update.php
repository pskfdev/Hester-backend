<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: *");
require_once '../../config/db.php';
require_once '../util/response.php';

$response = new Response();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    try {
        
        $data = array();
        $id = $_GET['id'];
        $req_data = json_decode(file_get_contents("php://input"));

        $stmt = $db_con->prepare("UPDATE blog SET name = :name, description = :description WHERE id = :id");
        $stmt->bindParam("id", $id);
        $stmt->bindParam("name", $req_data->name);
        $stmt->bindParam("description", $req_data->description);

        if ($stmt->execute()) {
            $data["id"] = $id;
            $data["category"] = $req_data->name;
            $data["description"] = $req_data->description;

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
