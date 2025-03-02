<?php

require_once("../ceramics_db_connect.php");

if (!isset($_POST["account"])) {
    die("請循正常管道進入此頁");
}

$account = $_POST["account"];
$password = $_POST["password"];
// $repassword = $_POST["repassword"];

$sql = "SELECT * FROM users WHERE account='$account' AND password='$password'";
$result = $conn->query($sql);
$userCount = $result->num_rows;

//帳號長度檢查

if (strlen($account) < 3 || strlen($account) > 20) {
    echo "<script>alert('請輸入3~20字元的帳號'); window.location.href='sign-in.php';</script>";
    exit();
}



//密碼長度檢查

if (strlen($password) < 5 || strlen($password) > 20) {
    echo "<script>alert('請輸入5~20字元的密碼'); window.location.href='sign-in.php';</script>";
    exit();
}



//帳號密碼是否存在

if ($userCount == 0) {
    // 帳號或密碼錯誤
    echo "<script>alert('帳號或密碼錯誤'); window.location.href='sign-in.php';</script>";
    exit();
}
//帳號密碼正確
header("location: users.php");
exit();






// if ($userCount == 1) {
//     die("該帳號已註冊");
// }
// if ($userCount == 0){
//     die("帳號或密碼錯誤");
// }
// if ($password != $repassword) {
//     die("密碼不一致");
// }




