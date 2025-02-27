<?php
require_once("../ceramics_db_connect.php");

if(!isset($_POST["account"])){
    die("請循正常管道進入此頁");
}

$account=$_POST["account"];
$password=$_POST["password"];
$name=$_POST["name"];
$email=$_POST["email"];
$phone=$_POST["phone"];
$gender=$_POST["gender"];
$birth = date("Y-m-d", strtotime($_POST["birth"]));
$now=date("Y-m-d H:i:s");

$sql="INSERT INTO users (account, password, name, email, phone, gender, birth, created_at) VALUES ('$account', '$password', '$name', '$email', '$phone', '$gender', '$birth', '$now')";
// echo $sql;

if ($conn->query($sql) === TRUE) {
    $last_id = $conn->insert_id;
    echo "新資料輸入成功, id 為 $last_id";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

header("location: sign-up.php");