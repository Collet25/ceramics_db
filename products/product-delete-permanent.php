<?php
require_once("../ceramics_db_connect.php");

header('Content-Type: application/json');

if($_SERVER["REQUEST_METHOD"] == "POST"){
    try {
        if(!isset($_POST["id"])){
            throw new Exception("缺少商品ID");
        }

        // 先檢查商品是否存在且已被刪除
        $checkSql = "SELECT id, image FROM products WHERE id = ? AND deleted_at IS NOT NULL";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("i", $_POST["id"]);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        
        if($result->num_rows === 0){
            throw new Exception("找不到該已刪除商品");
        }

        // 獲取商品圖片檔名，以便後續刪除圖片檔案
        $product = $result->fetch_assoc();
        $image_path = "../uploads/" . $product["image"];

        // 永久刪除商品資料
        $sql = "DELETE FROM products WHERE id = ? AND deleted_at IS NOT NULL";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_POST["id"]);
        
        if(!$stmt->execute()){
            throw new Exception("商品刪除失敗: " . $conn->error);
        }

        // 如果資料庫刪除成功，也刪除圖片檔案
        if(file_exists($image_path)){
            unlink($image_path);
        }

        echo json_encode([
            "success" => true,
            "message" => "商品已永久刪除"
        ]);

    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "不允許的請求方法"
    ]);
}
?> 