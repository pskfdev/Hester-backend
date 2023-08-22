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
        $req_data = json_decode(file_get_contents("php://input"));
        $role = "user";

        $check_user = $db_con->prepare("SELECT username FROM users WHERE username=:username");
        $check_user->bindParam(':username', $req_data->username);
        $check_user->execute();
        $rows = $check_user->fetch(PDO::FETCH_ASSOC);

        if ($rows) {
            $response->error('User Already exists');
        } else {
            $passwordHash = password_hash($req_data->password, PASSWORD_DEFAULT);
            $stmt_regis = $db_con->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
            $stmt_regis->bindParam(':username', $req_data->username);
            $stmt_regis->bindParam(':password', $passwordHash);
            $stmt_regis->bindParam(':role', $role);

            if ($stmt_regis->execute()) {
                $data["username"] = $req_data->username;
                $data["role"] = $role;

                $response->success($data, 'success');
            } else {
                $response->error('error can not register!!');
            }
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