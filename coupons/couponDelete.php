<!-- 軟刪除：並非實質刪除而是加入到存放刪除資料的表中 -->
<?php
require_once("../ceramics_db_connect.php");
var_dump($_GET);

$id=$_GET["id"];
$sql="UPDATE coupons SET valid=0 WHERE id=$id";
$result=$conn->query($sql);

$conn->close();

header("location: coupon.php");

