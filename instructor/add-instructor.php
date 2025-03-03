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
$target_dir1 = "../instructor_img/"; // 圖片存放目錄
$target_file1 = $target_dir1 . basename($_FILES["teacherImg"]["name"]);

// 確保只允許上傳圖片
$allowed_types1 = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($_FILES["teacherImg"]["type"], $allowed_types1)) {
    die("只允許上傳 JPG, PNG, GIF 圖片");
}

move_uploaded_file($_FILES["teacherImg"]["tmp_name"], $target_file1);

$sql1 = "INSERT INTO instructor (name, img, gender, phone, email, bio, created_at, valid) 
 VALUES ('$name', '$target_file1', '$gender', '$phone', '$email', '$bio', '$now', '$valid')";


if ($conn->query($sql1) === TRUE) {
    $last_id = $conn->insert_id;
    echo "新資料輸入成功, id 為 $last_id";
} else {
    echo "Error: " . $sql1 . "<br>" . $conn->error;
}

$artname=$_POST["artName"];
$artbio=$_POST["artBio"];
// 上傳圖片
$target_dir2 = "../artwork_img/"; // 圖片存放目錄
$target_file2 = $target_dir2 . basename($_FILES["artImg"]["name"]);
// 確保只允許上傳圖片
$allowed_types2 = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($_FILES["artImg"]["type"], $allowed_types2)) {
    die("只允許上傳 JPG, PNG, GIF 圖片");
}
move_uploaded_file($_FILES["artImg"]["tmp_name"], $target_file2);

$sql2= "INSERT INTO artwork (artname, artBio, image, instructor_id)
VALUES ('$artname', '$artbio', '$target_file2', '$last_id')";

if ($conn->query($sql2) === TRUE) {
    echo "藝術作品插入成功";
} else {
    echo "Error: " . $sql2 . "<br>" . $conn->error;
}

$conn->close();

header("location: instructors.php");
