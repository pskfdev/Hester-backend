<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: *");
require_once '../../config/db.php';
require_once '../util/response.php';

try {
    $response = new Response();
    $data = array();

    $stmt = $db_con->prepare("SELECT * FROM product");
    $stmt->execute();
    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $rows;/* จะดึงข้อมูลมาทีละแถว */
    }
    /* echo json_encode($data); */
    $response->success($data, 'success');
    
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
