<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Content-Type: application/json; charset=UTF-8");
require_once '../../config/db.php';
require_once '../util/response.php';
require __DIR__ . '../../../vendor/autoload.php';
use Firebase\JWT\JWT;

$response = new Response();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    try {
        $req_data = json_decode(file_get_contents("php://input"));
        $stmt = $db_con->prepare("SELECT * FROM users WHERE username=:username");
        $stmt->bindParam(':username', $req_data->username);
        $stmt->execute();

        if ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (password_verify($req_data->password, $rows['password'])) {

                $key = 'pongsakan fluk';
                $payload = [
                    'iss' => 'localhost',
                    'aud' => 'localhost',
                    'exp' => time() + 10000,
                    'data' => [
                        'id' => $rows['id'],
                        'username' => $rows['username'],
                        'password' => $rows['password'],
                    ],
                ];

                $jwt = JWT::encode($payload, $key, 'HS256');
                echo json_encode([
                    'status' => true,
                    'response' => [
                        'username' => $rows['username'],
                        'token' => $jwt,
                        'role' => $rows['role']
                    ],
                    'message' => 'success'
                ]);
            } else {
                $response->error('Password Invalid!!');
            }
        } else {
            $response->error('error User not found!!');
        }
        
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
} else {
    $response->error('Error Method!!');
    die();
}
