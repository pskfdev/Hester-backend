<?php
header("Access-Control-Allow-Origin: *"); //รองรับการเรียกแบบข้าม Domain
header("Content-Type: application/json; charset=UTF-8"); //ให้ส่งข้อมูลในรูปแบบ Json
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: *");
require_once '../../config/db.php';
require_once '../util/response.php';

$response = new Response();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    try {
        
        $data = array();
        /* $req_data = json_decode(file_get_contents("php://input")); */

        $category = $_POST['category'];
        $img = $_FILES['img'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $title = $_POST['title'];
        
        $allow = array('jpg', 'jpeg', 'png');
        $typeFile = explode(".", $img['name']);/* แยกนามสกุลไฟล์ */
        $typeLower = strtolower(end($typeFile)); /* นามสกุลไฟล์แปลงเป็นพิมเล็ก */
        $fileNew = rand() . "." . $typeLower; /* ตั้งชื่อไฟล์ใหม่ */
        $filePath = "../uploads/" . $fileNew;

        if (in_array($typeLower, $allow)) {
            if ($img['size'] > 0 && $img['error'] == 0) {
                if (move_uploaded_file($img['tmp_name'], $filePath)) {

                    $stmt = $db_con->prepare("INSERT INTO product (category, img, price, description, title) VALUES (:category, :img, :price, :description, :title)");
                    $stmt->bindParam("category", $category);
                    $stmt->bindParam("img", $fileNew);
                    $stmt->bindParam("price", $price);
                    $stmt->bindParam("description", $description);
                    $stmt->bindParam("title", $title);

                    if ($stmt->execute()) {
                        $data["category"] = $category;
                        $data["img"] = $fileNew;
                        $data["price"] = $price;
                        $data["description"] = $description;
                        $data["title"] = $title;

                        $response->success($data, 'success');

                    } else {
                        $response->error('error can not create!');
                    }
                    /* echo json_encode($data); */
                }
            }
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
} else {
    $response->error('Error Method!!');
    die();
}
