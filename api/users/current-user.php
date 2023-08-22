<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: *");
require_once '../../config/db.php';
require_once '../util/response.php';
require __DIR__ . '../../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$response = new Response();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {
        $req_data = json_decode(file_get_contents("php://input"));
        $token = $req_data->token;

        if (!$token) {
            return $response->error('No token authorization denied!!');
        }

        $key = 'pongsakan fluk';
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        $username = $decoded->data->username;

        $stmt = $db_con->prepare("SELECT * FROM users WHERE username=:username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data = array();
            $data["username"] = $rows['username'];
            $data["role"] = $rows['role'];
            $response->success($data, 'success');
        } else {
            $response->error('Token Invalid!!');
        }
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
} else {
    $response->error('Error Method!!');
    die();
}

?>
