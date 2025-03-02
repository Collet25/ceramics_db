<?php
require_once("../ceramics_db_connect.php");

if (!isset($_POST["teacherName"])) {
    die("請循正常管道進入此頁");
}

$name = $_POST["teacherName"];
$email = $_POST["teacherEmail"];
$gender = $_POST["teacherGender"];
$phone = $_POST["teacherPhone"];
$bio = $_POST["teacherBio"];
$now=date("Y-m-d H:i:s");
$valid=1;

// 上傳圖片
$target_dir = "../instructor_img"; // 圖片存放目錄
$target_file = $target_dir . basename($_FILES["teacherImg"]["name"]);

// 確保只允許上傳圖片
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($_FILES["teacherImg"]["type"], $allowed_types)) {
    die("只允許上傳 JPG, PNG, GIF 圖片");
}

move_uploaded_file($_FILES["teacherImg"]["tmp_name"], $target_file);

$sql = "INSERT INTO instructor (name, img, gender, phone, email, bio, created_at, valid) 
 VALUES ('$name', '$target_file', '$gender', '$phone', '$email', '$bio', '$now', '$valid')";

if ($conn->query($sql) === TRUE) {
    $last_id = $conn->insert_id;
    echo "新資料輸入成功, id 為 $last_id";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

header("location: instructors.php");
