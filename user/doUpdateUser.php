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

$sql = "UPDATE users SET name='$name', phone='$phone', email='$email', gender='$gender', birth='$birth' WHERE id='$id'";
echo $sql;

if ($conn->query($sql) === TRUE) {
    // echo "資料更新成功";
    header("location:user.php?id=$id");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
