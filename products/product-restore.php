<?php
require_once("../ceramics_db_connect.php");

header('Content-Type: application/json');

if($_SERVER["REQUEST_METHOD"] == "POST"){
    try {
        if(!isset($_POST["id"])){
            throw new Exception("缺少商品ID");
        }

        // 先檢查商品是否存在且已被刪除
        $checkSql = "SELECT id FROM products WHERE id = ? AND deleted_at IS NOT NULL";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("i", $_POST["id"]);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        
        if($result->num_rows === 0){
            throw new Exception("找不到該已刪除商品");
        }

        // 還原商品：將 deleted_at 設為 NULL
        $sql = "UPDATE products SET deleted_at = NULL WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_POST["id"]);
        
        if(!$stmt->execute()){
            throw new Exception("商品還原失敗: " . $conn->error);
        }

        echo json_encode([
            "success" => true,
            "message" => "商品已還原"
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