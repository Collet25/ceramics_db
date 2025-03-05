<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("../ceramics_db_connect.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM exhibition WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        // 刪除成功，跳轉回展覽清單頁面並顯示刪除成功訊息
        header("Location: exhibition-list.php?message=deleted");
        exit();  // 確保腳本停止執行
    } else {
        echo "錯誤: " . mysqli_error($conn);
    }
} else {
    // 如果未提供 ID，跳回清單頁面
    header("Location: exhibition-list.php?error=no_id");
    exit();  // 確保腳本停止執行
}
?>


