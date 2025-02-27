<?php

require_once("../ceramics_db_connect.php");

if (!isset($_POST["account"])) {
    die("請循正常管道進入此頁");
}

$account = $_POST["account"];
$password = $_POST["password"];
$repassword = $_POST["repassword"];

$sql = "SELECT * FROM users WHERE account='$account'";
$result = $conn->query($sql);
$userCount = $result->num_rows;

// $patternAccount="/^.{4,12}$/";

if (strlen($account) < 3 || strlen($account) > 20) {
    die("請輸入3~20字元的帳號");
}
// if(preg_match($patternAccount, $account)){
//     die("請輸入3~20字元的帳號");
// }

// if ($userCount == 1) {
//     die("該帳號已註冊");
// }
if ($userCount == 0){
    die("帳號或密碼錯誤");
}

if (strlen($password < 5 || strlen($password) > 20)) {
    die("請輸入5~20字元的密碼");
}

if ($password != $repassword) {
    die("密碼不一致");
}

//加密
// $password=md5($password);

// $now = date("Y-m-d H:i:s");
// $sql = "INSERT INTO users (account, password, created_at, valid) 
//     VALUES ('$account', '$password', '$now', 1)";

// if ($conn->query($sql) === TRUE) {

// } else {
//     echo "Error: " . $sql . "<br>" . $conn->error;
//     die;
// }

// $conn->close();

// header("location: create-user.php");