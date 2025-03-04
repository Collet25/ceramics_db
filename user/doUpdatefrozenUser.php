<?php
if (!isset($_POST["name"])) {
    die("請循正常管道進入此頁");
}

require_once("../ceramics_db_connect.php");

$id = $_POST["id"];
$name = $_POST["name"];
$phone = $_POST["phone"];
$email = $_POST["email"];
$gender=$_POST["gender"];
$birth = date("Y-m-d", strtotime($_POST["birth"]));
$frozen = $_POST["frozen"];

if($_FILES["image"]["error"] == 0){

    $ext=pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

    $filename=time(). "." .$ext;
    // echo $ext
    if(move_uploaded_file($_FILES["image"]["tmp_name"],"../user-upload/".$filename)){
        // echo "上傳成功";
    }else{
        echo "上傳檔案失敗";
        exit;
    }
}else{
     echo"圖片上傳錯誤";
}

$sql = "UPDATE users SET name='$name', phone='$phone', email='$email', gender='$gender', birth='$birth', frozen='$frozen'";
// echo $sql;

if ($filename != "") {
    // 如果有上傳圖片，則更新圖片檔名
    $sql .= ", image='$filename'";
}
$sql .= " WHERE id='$id'";


if ($conn->query($sql) === TRUE) {
    // echo "資料更新成功";
    header("location:frozenUser.php?id=$id");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
