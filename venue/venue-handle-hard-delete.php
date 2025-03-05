<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("../ceramics_db_connect.php");

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // 刪除場地前先刪除相關的預約資料
    $deleteBookings = "DELETE FROM bookings WHERE venue_id = $id";
    if (!mysqli_query($conn, $deleteBookings)) {
        echo "刪除預約資料時發生錯誤: " . mysqli_error($conn);
        exit;
    }

    // 刪除場地資料
    $sql = "DELETE FROM venue WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        header("Location: venue-list.php");
        exit;
    } else {
        echo "刪除場地資料時發生錯誤: " . mysqli_error($conn);
    }
} else {
    echo "無效的請求";
}
