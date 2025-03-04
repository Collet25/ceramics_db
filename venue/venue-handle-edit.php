<!-- 負責接收 venue-edit.php 表單送出的資料，更新資料庫。 -->

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php
require_once("../ceramics_db_connect.php");

$id = $_POST["id"];
$name = $_POST["name"];
$description = $_POST["description"];

// 如果有上傳新圖片
if ($_FILES["image"]["error"] == 0) {
    $imageName = time() . "_" . $_FILES["image"]["name"];
    $targetDir = "../uploads/venues/";
    $targetFile = $targetDir . $imageName;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        $sql = "UPDATE venue SET name='$name', image='$imageName', description='$description' WHERE id='$id'";
    } else {
        echo "圖片上傳失敗，請重試";
        exit();
    }
} else {
    $sql = "UPDATE venue SET name='$name', description='$description' WHERE id='$id'";
}

if ($conn->query($sql) === TRUE) {
    header("Location: venue-list.php");
} else {
    echo "更新失敗: " . $conn->error;
}
