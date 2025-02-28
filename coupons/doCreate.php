<?php
require_once("../ceramics_db_connect.php");

if(!isset($_POST["name"])){
    die("請循正常管道進入此頁");
}

// print_r($_POST);
// exit();

$name=$_POST["name"];
$code=$_POST["code"];
$categories=$_POST["categories"];
$discountType=$_POST["discountType"];
$discount=$_POST["discount"];
$discount=preg_replace("/[^0-9.]/", "", $discount); // 移除 $ 或 % 符號
$minSpend=$_POST["minSpend"];
$quantity=$_POST["quantity"];
$startDate=$_POST["startDate"];
$endDate=$_POST["endDate"];
$status = $_POST['status']; // 啟用停用
$now=date("Y-m-d H:i:s");


$sql = "INSERT INTO coupons (name, code, categories, discountType, discount, minSpend, quantity, startDate, endDate, status, created_at, updated_at) 
        VALUES ('$name', '$code', '$categories', '$discountType', '{$_POST["discount"]}', '$minSpend', '$quantity', '$startDate', '$endDate', '$status', '$now', '$now')";


if ($conn->query($sql) === TRUE) {
    $last_id = $conn->insert_id;
    echo "新資料輸入成功, id 為 $last_id";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

header("location: coupon.php");

?>


<!-- // 驗證日期
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $startDate = $_POST["startDate"];
//     $endDate = $_POST["endDate"];

//     if (strtotime($startDate) > strtotime($endDate)) {
//         die("錯誤：開始日期不能晚於結束日期！");
//     }
//     // 通過驗證，繼續執行資料庫插入操作
// }
 -->
