<?php
header("Access-Control-Allow-Origin: *"); //รองรับการเรียกแบบข้าม Domain
header("Content-Type: application/json; charset=UTF-8"); //ให้ส่งข้อมูลในรูปแบบ Json
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: *");
require_once '../../config/db.php';
require_once '../util/response.php';

try {
    $response = new Response();
    $data = array();

    $stmt = $db_con->prepare("SELECT * FROM blog");
    $stmt->execute();
    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $rows;/* จะดึงข้อมูลมาทีละแถว */
    }
    $response->success($data, 'success');
    
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
