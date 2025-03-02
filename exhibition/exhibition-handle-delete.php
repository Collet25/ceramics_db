<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("../ceramics_db_connect.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM exhibition WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        echo "展覽已成功刪除！";
    } else {
        echo "錯誤: " . mysqli_error($conn);
    }
}
?>

