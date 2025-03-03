<?php
require_once("../ceramics_db_connect.php");

// 设置响应头为JSON
header('Content-Type: application/json; charset=utf-8');

// 错误处理函数
function returnError($message) {
    echo json_encode([
        'success' => false,
        'message' => $message
    ]);
    exit;
}

try {
    // 检查必要的POST数据
    if (!isset($_POST["id"]) || !isset($_POST["name"]) || !isset($_POST["price"])) {
        returnError("缺少必要的表單數據");
    }

    $id = $_POST["id"];
    $name = $_POST["name"];
    $category = $_POST["category"];
    $subcategory = $_POST["subcategory"];
    $price = $_POST["price"];
    $description = $_POST["description"];
    $material = $_POST["material"];
    $origin = $_POST["origin"];
    $old_image = $_POST["old_image"];

    // 处理图片上传
    $image = $old_image; // 默认使用原图片
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {
        $target_dir = "../uploads/";
        $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        
        // 检查文件类型
        if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
            returnError("只允許上傳 JPG, JPEG, PNG 與 GIF 格式的圖片");
        }

        // 生成新的文件名
        $image = uniqid() . "." . $imageFileType;
        $target_file = $target_dir . $image;

        // 移动上传的文件
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            returnError("圖片上傳失敗");
        }

        // 如果上传成功且有旧图片，删除旧图片
        if ($old_image && file_exists($target_dir . $old_image)) {
            unlink($target_dir . $old_image);
        }
    }

    // 更新数据库
    $sql = "UPDATE products SET 
            name=?, category=?, subcategory=?, price=?, 
            description=?, image=?, material=?, origin=?, 
            updated_at=CURRENT_TIMESTAMP 
            WHERE id=?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", 
        $name, $category, $subcategory, $price, 
        $description, $image, $material, $origin, $id
    );

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => '商品更新成功'
        ]);
    } else {
        returnError("資料庫更新失敗: " . $stmt->error);
    }

} catch (Exception $e) {
    returnError("系統錯誤: " . $e->getMessage());
}

$conn->close();
?>