<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: *");
include '../../config/db.php';
require_once '../util/response.php';

try {
    $response = new Response();
    $data = array();

    $stmt = $db_con->prepare("SELECT * FROM category");
    $stmt->execute();
    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $rows; /* จะดึงข้อมูลมาทีละแถว */
    }
    $response->success($data, 'success');
    
} catch (PDOException $e) {
    echo $e->getMessage();
    die();
}
