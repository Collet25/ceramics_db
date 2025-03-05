<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php
require_once("../ceramics_db_connect.php");

// 處理篩選時間
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// 基本查詢語句
$sql = "SELECT b.*, v.name AS venue_name, u.name AS user_name, e.title AS exhibition_title
        FROM bookings b
        JOIN venue v ON b.venue_id = v.id
        LEFT JOIN users u ON b.user_id = u.id
        LEFT JOIN exhibition e ON b.exhibition_id = e.id";

// 如果有篩選時間，加入篩選條件
if ($start_date && $end_date) {
    // 確保返回篩選時間內的重疊預約
    $sql .= " WHERE (b.start_time BETWEEN ? AND ?) OR (b.end_time BETWEEN ? AND ?) OR (b.start_time <= ? AND b.end_time >= ?)";
} elseif ($start_date) {
    // 只篩選開始時間在範圍內的預約
    $sql .= " WHERE b.start_time >= ?";
} elseif ($end_date) {
    // 只篩選結束時間在範圍內的預約
    $sql .= " WHERE b.end_time <= ?";
}

// 準備查詢並執行
$stmt = $conn->prepare($sql);

// 如果有篩選時間，綁定參數
if ($start_date && $end_date) {
    $stmt->bind_param("ssssss", $start_date, $end_date, $start_date, $end_date, $start_date, $end_date);
} elseif ($start_date) {
    $stmt->bind_param("s", $start_date);
} elseif ($end_date) {
    $stmt->bind_param("s", $end_date);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../logo-img/head-icon.png">
    <title>預約列表</title>
    <?php include("../css.php"); ?>
    <?php include("../ev-css.php"); ?>
</head>

<body>
    <?php include("../aside.php"); ?>
    <main class="main-content position-relative max-height-vh-100 border-radius-lg">
        <?php include("../navbar.php"); ?>

        <div class="container-fluid px-5 py-3">
            <h2 class="mb-5">預約列表</h2>

            <!-- 時間篩選表單 -->
            <form method="GET" action="booking-list.php">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($start_date) ?>">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($end_date) ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">篩選</button>
                    </div>
                    <!-- 返回總清單按鈕 -->
                    <div class="col-md-2">
                        <a href="booking-list.php" class="btn btn-secondary">返回</a>
                    </div>
                </div>
            </form>

            <table class="table">
                <thead>
                    <tr>
                        <th>場地名稱</th>
                        <th>預約者</th>
                        <th>開始時間</th>
                        <th>結束時間</th>
                        <th>展覽名稱</th> <!-- 新增展覽名稱欄位 -->
                        <th>狀態</th> <!-- 新增狀態欄位 -->
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['venue_name']) ?></td>
                                <td><?= htmlspecialchars($row['user_name'] ?: 'admin') ?></td> <!-- 若 user_name 為 NULL 顯示 'admin' -->
                                <td><?= htmlspecialchars($row['start_time']) ?></td> <!-- 使用 start_time -->
                                <td><?= htmlspecialchars($row['end_time']) ?></td>   <!-- 使用 end_time -->
                                <td><?= isset($row['exhibition_title']) && !empty($row['exhibition_title']) ? htmlspecialchars($row['exhibition_title']) : '無' ?></td> <!-- 顯示展覽名稱，若為 NULL 顯示 "無" -->
                                <td>
                                    <!-- 根據 status 顯示不同狀態 -->
                                    <?php 
                                        if ($row['status'] == 'pending') {
                                            echo '<span class="text-warning">待確認</span>';
                                        } elseif ($row['status'] == 'confirmed') {
                                            echo '<span class="text-success">已確認</span>';
                                        } else {
                                            echo '<span class="text-danger">已取消</span>';
                                        }
                                    ?>
                                </td> <!-- 顯示狀態 -->
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">目前沒有預約資料</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php include("../footer.php"); ?>
    </main>
    <?php include("../js.php"); ?>
</body>

</html>
