<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: *");
require_once '../../config/db.php';
require_once '../util/response.php';


try {
    $response = new Response();
    $id = $_GET['id'];

    $stmt = $db_con->prepare("SELECT * FROM blog WHERE id=:id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $response->success($rows, 'success');
    }else {
        $response->error('error not found!');
    }
    
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

?>
