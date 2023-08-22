<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: *");
require_once '../../config/db.php';
require_once '../util/response.php';

$response = new Response();
if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    try {
        
        $id = $_GET['id'];

        //Select data
        $select_stmt = $db_con->prepare("SELECT * FROM blog WHERE id = :id ");
        $select_stmt->bindParam("id", $id);
        $select_stmt->execute();
        $rows = $select_stmt->fetch(PDO::FETCH_ASSOC);

        //Delete data
        $delete_stmt = $db_con->prepare("DELETE FROM blog WHERE id = :id");
        $delete_stmt->bindParam("id", $id);

        if ($delete_stmt->execute()) {
            $response->success($rows, 'success');
        } else {
            $response->error('error can not delete!');
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
} else {
    $response->error('Error Method!!');
    die();
}
