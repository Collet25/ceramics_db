<?php
require_once("../ceramics_db_connect.php");

if (!isset($_POST["teacherName"])) {
    die("請循正常管道進入此頁");
}

$id = $_POST["id"];
$name = $_POST["teacherName"];
$email = $_POST["teacherEmail"];
$gender = $_POST["teacherGender"];
$phone = $_POST["teacherPhone"];
$bio = $_POST["teacherBio"];


// 先查詢現有的圖片
$sql = "SELECT img FROM instructor WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$old_img = $row["img"];
// 確保有上傳新圖片
if (isset($_FILES["teacherImg"]) && $_FILES["teacherImg"]["error"] === UPLOAD_ERR_OK) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($_FILES["teacherImg"]["name"]);
    move_uploaded_file($_FILES["teacherImg"]["tmp_name"], $target_file);
} else {
    $target_file = $old_img; // 如果沒有上傳新圖片，使用舊圖片
}


$sql = "UPDATE instructor SET  name='$name', img='$target_file', gender='$gender', phone='$phone', email='$email', bio='$bio' WHERE id='$id'";
// echo $sql;
if ($conn->query($sql) === TRUE) {
    echo "資料更新成功！";
} else {
    echo "更新失敗：" . $conn->error;
}

$conn->close();

header("Location: instructor.php");
exit();
