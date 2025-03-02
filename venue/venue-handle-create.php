<?php
require_once("../ceramics_db_connect.php");

// 設定上傳目錄
$targetDir = "../uploads/venues/";
$targetFile = $targetDir . basename($_FILES["image"]["name"]);
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// 檢查是否是圖片
$check = getimagesize($_FILES["image"]["tmp_name"]);
if ($check === false) {
    echo "檔案不是圖片，請重試";
    exit;
}

// 檢查檔案格式
$allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
if (!in_array($imageFileType, $allowedTypes)) {
    echo "只允許上傳 JPG, JPEG, PNG, GIF 格式的圖片";
    exit;
}

// 上傳圖片
if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
    // 只儲存檔名到資料庫
    $image = basename($_FILES["image"]["name"]);
} else {
    echo "圖片上傳失敗，請重試";
    exit;
}

// 插入資料到資料庫
$name = $_POST['name'];
$description = $_POST['description'];
$category = $_POST['category'];
$capacity = $_POST['capacity'];

$sql = "INSERT INTO venue (name, image, description, category, capacity, status) VALUES ('$name', '$image', '$description', '$category', $capacity, 1)";
if ($conn->query($sql) === TRUE) {
    header("Location: venue-list.php");
} else {
    echo "新增失敗: " . $conn->error;
}

$conn->close();
