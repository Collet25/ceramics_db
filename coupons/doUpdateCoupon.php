<?php
// if (!isset($_POST["id"])) {
//     die("請循正常管道進入此頁");
// }

require_once("../ceramics_db_connect.php");

$id = $_POST["id"];
$name = $_POST["name"];
// $code = $_POST["code"];
$categories = $_POST["categories"];
$discountType = $_POST["discountType"];
$discountA = $_POST["discount"];
$discount = preg_replace("/[^0-9.]/", "", $discountA); // 移除 $ 或 % 符號
$minSpend = $_POST["minSpend"];
$quantity = $_POST["quantity"];
$startDate = $_POST["startDate"];
$endDate = $_POST["endDate"];
$status = $_POST['status'];
$now = date("Y-m-d H:i:s");

// var_dump($_POST); // 檢查所有 POST 變數
// die(); // 暫停程式執行

$sql = "UPDATE coupons SET name='$name', categories='$categories', discountType='$discountType', discount='$discount', minSpend='$minSpend', quantity='$quantity', startDate='$startDate', endDate='$endDate',status='$status', updated_at='$now' WHERE id='$id'";
// echo $sql;

if ($conn->query($sql) === TRUE) {
    // echo "資料更新成功";
    header("location: coupon-detail.php?id=$id");
    ob_end_flush(); // 清空緩衝區並輸出
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
