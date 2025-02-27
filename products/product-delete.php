<?php
require_once("../ceramics_db_connect.php");

header('Content-Type: application/json');

if($_SERVER["REQUEST_METHOD"] == "POST"){
    try {
        if(!isset($_POST["id"])){
            throw new Exception("缺少商品ID");
        }

        // 軟刪除：更新 deleted_at 欄位
        $sql = "UPDATE products SET deleted_at = NOW() WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_POST["id"]);
        
        if(!$stmt->execute()){
            throw new Exception("商品刪除失敗");
        }

        echo json_encode([
            "success" => true,
            "message" => "商品已移至回收桶"
        ]);

    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => $e->getMessage()
        ]);
    }
}
?>