<!-- 軟刪除 -->

<?php
require_once("../ceramics_db_connect.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "UPDATE venue SET status = 0 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: venue-list.php");
    } else {
        echo "下架失敗，請重試";
    }
} else {
    echo "未指定場地 ID";
}
