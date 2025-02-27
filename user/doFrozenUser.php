<?php
// if (!isset($_POST["id"])) {
//     die("請循正常管道進入此頁");
// }
require_once("../ceramics_db_connect.php");

$id=$_GET["id"];
$sql="UPDATE users SET valid=1 WHERE id=$id";
$result=$conn->query($sql);

$conn->close();

header("location: users.php");
