<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: *");
include '../../config/db.php';
require_once '../util/response.php';

$response = new Response();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    try {
        
        $data = array();
        $req_data = json_decode(file_get_contents("php://input"));
        $id = $_GET['id'];

        $stmt = $db_con->prepare("UPDATE category SET name = :name WHERE id = :id");
        $stmt->bindParam("id", $id);
        $stmt->bindParam("name", $req_data->name);

        if ($stmt->execute()) {
            $data["id"] = $id;
            $data["category"] = $req_data->name;

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
