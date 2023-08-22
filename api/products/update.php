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

        $id = $_GET['id'];
        /* $req_data = json_decode(file_get_contents("php://input")); */
        $category = $_POST['category'];
        $img = $_POST['img'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $title = $_POST['title'];

        isset($_FILES['imgNew']) ? $imgNew = $_FILES['imgNew'] : $imgNew = "";

        isset($_FILES['imgNew']['name']) ? $upload = $_FILES['imgNew']['name'] : $upload = "";

        if ($upload != "") {
            $allow = array('jpg', 'jpeg', 'png');
            $typeFile = explode(".", $imgNew['name']);/* แยกนามสกุลไฟล์ */
            $typeLower = strtolower(end($typeFile)); /* นามสกุลไฟล์แปลงเป็นพิมเล็ก */
            $fileNew = rand() . "." . $typeLower; /* ตั้งชื่อไฟล์ใหม่ */
            $filePath = "../uploads/" . $fileNew;

            if (in_array($typeLower, $allow)) {
                if ($imgNew['size'] > 0 && $imgNew['error'] == 0) {
                    move_uploaded_file($imgNew['tmp_name'], $filePath);
                    unlink("../uploads/" . $img);
                }
            }
        } else {
            $fileNew = $img;
        }

        $stmt = $db_con->prepare("UPDATE product SET category = :category, img = :img, price = :price, description = :description, title = :title WHERE id = :id");

        $stmt->bindParam("category", $category);
        $stmt->bindParam("img", $fileNew);
        $stmt->bindParam("price", $price);
        $stmt->bindParam("description", $description);
        $stmt->bindParam("title", $title);
        $stmt->bindParam("id", $id);

        if ($stmt->execute()) {
            $data["category"] = $category;
            $data["img"] = $fileNew;
            $data["price"] = $price;
            $data["description"] = $description;
            $data["title"] = $title;

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
?>
