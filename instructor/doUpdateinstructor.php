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
    $target_dir = "../instructor_img/";
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

// 更新 artwork 資料表
//   (a) 先查詢現有作品圖
$sqlOldArtImg = "SELECT image FROM artwork WHERE instructor_id = $id";
$resultArtOld = $conn->query($sqlOldArtImg);
$rowArtOld = $resultArtOld->fetch_assoc();
$old_art_img = $rowArtOld["image"] ?? null;

//   (b) 處理新上傳作品圖
if (isset($_FILES["artImg"]) && $_FILES["artImg"]["error"] === UPLOAD_ERR_OK) {
    $artwork_dir = "../artwork_img/";
    if (!is_dir($artwork_dir)) {
        mkdir($artwork_dir, 0777, true);
    }
    $artwork_file = $artwork_dir . basename($_FILES["artImg"]["name"]);
    move_uploaded_file($_FILES["artImg"]["tmp_name"], $artwork_file);
} else {
    $artwork_file = $old_art_img; // 若沒上傳新作品圖，沿用舊圖
}

//   (c) 取得表單中作品名稱 (若有作品介紹也一起)
$artName = $_POST["artName"] ?? "";
// 如果 artwork 資料表還有其他欄位 (如作品介紹)，可繼續取值
$artBio = $_POST["artBio"] ?? "";

//   (d) 檢查是否已經有一筆對應 instructor_id 的作品
$sqlCheck = "SELECT id FROM artwork WHERE instructor_id = $id";
$resultCheck = $conn->query($sqlCheck);

if ($resultCheck->num_rows > 0) {
    // 已經有作品 -> 做 UPDATE
    $sqlArtwork = "UPDATE artwork 
                   SET artname='$artName',
                       artbio='$artBio',
                       image='$artwork_file'
                   WHERE instructor_id='$id'";
} else {
    // 沒有作品 -> 做 INSERT (若你想確保一定要有作品資料)
    $sqlArtwork = "INSERT INTO artwork (artname, artbio, image, instructor_id) 
                   VALUES ('$artName', '$artBio', '$artwork_file', '$id')";
}

if (!$conn->query($sqlArtwork)) {
    die("更新/插入作品失敗: " . $conn->error);
}

// 全部更新成功後，導回單一老師頁面或列表
$conn->close();

// 假設你有一個單一老師資訊頁面 instructor.php?id=xxx
header("Location: instructor.php?id=$id");
exit;
