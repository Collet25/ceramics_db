<?php
if (!isset($_POST["id"])) {
    die("請循正常管道進入此頁");
}

require_once("../ceramics_db_connect.php");

$id = $_POST["id"];
// $name = $_POST["name"];
// $phone = $_POST["phone"];
// $email = $_POST["email"];
$frozen = $_POST["frozen"];

$sql = "UPDATE users SET frozen='$frozen' WHERE id='$id'";
echo $sql;

if ($conn->query($sql) === TRUE) {
    // echo "資料更新成功";
    header("location:frozenUser.php?id=$id");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
