<?php

$servername = "localhost";
$username = "admin";
$password = "12345";
$dbname = "ceramics_db";

// 設定現在時間
$now = date("Y-m-d H:i:s");

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname); //連接資料庫
// 檢查連線
if ($conn->connect_error) {
  	die("連線失敗: " . $conn->connect_error); //debug
}else{
    //echo "連線成功"; //連線成功
}

?>


