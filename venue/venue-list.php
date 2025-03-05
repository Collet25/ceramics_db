<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>


<?php
require_once("../ceramics_db_connect.php");

// 取得篩選參數，預設為全部
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$category = isset($_GET['category']) ? $_GET['category'] : 'all';

// 根據篩選參數設定 SQL 查詢條件
$where = [];  // 使用陣列來累積條件
if ($filter === 'active') {
    $where[] = "v.status = 1";  // 只顯示上架的場地
} elseif ($filter === 'inactive') {
    $where[] = "v.status = 0";  // 只顯示下架的場地
}

// 加入場地類型篩選條件
if ($category === 'exhibition') {
    $where[] = "v.category = 'exhibition'";  // 只顯示展覽廳
} elseif ($category === 'room') {
    $where[] = "v.category = 'room'";  // 只顯示教室
}

// 組合所有條件
$where_sql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

// 修改 SQL 查詢，加入「使用中」判斷
$sql = "
SELECT v.*, 
       COUNT(b.id) AS booking_count,
       CASE 
           WHEN SUM(CASE WHEN CURRENT_TIMESTAMP() BETWEEN b.start_time AND b.end_time THEN 1 ELSE 0 END) > 0 THEN '使用中'
           ELSE '可預約'
       END AS usage_status
FROM venue v
LEFT JOIN bookings b ON v.id = b.venue_id
$where_sql
GROUP BY v.id;
";

$result = $conn->query($sql);
?>




<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../logo-img/head-icon.png">
    <title>場地列表</title>
    <?php include("../css.php"); ?>
    <?php include("../ev-css.php"); ?>
</head>

<body>
    <?php include("../aside.php"); ?>
    <main class="main-content position-relative max-height-vh-100 border-radius-lg">
        <?php include("../navbar.php"); ?>

        <!-- 場地分類 -->
        <div class="m-auto w-70">
            <form class="row  mt-3 mx-2 text-center" method="GET">
                <div class="col">
                    <a href="?category=all" class="fil <?= $category === 'all' ? 'active' : '' ?>">全部類型</a>
                </div>
                <div class="col">
                    <a href="?category=exhibition" class="fil <?= $category === 'exhibition' ? 'active' : '' ?>">展覽廳</a>
                </div>
                <div class="col">
                    <a href="?category=room" class="fil <?= $category === 'room' ? 'active' : '' ?>">教室</a>
                </div>
            </form>
            <hr>
        </div>




        <!-- 場地列表表格 -->
        <div class="container-fluid px-5 py-3 align-items-center">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-5">場地列表</h2>
                <form method="GET" class="d-inline-block">
                    <select name="filter" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="all" <?= $filter === 'all' ? 'selected' : '' ?>>全部</option>
                        <option value="active" <?= $filter === 'active' ? 'selected' : '' ?>>已上架</option>
                        <option value="inactive" <?= $filter === 'inactive' ? 'selected' : '' ?>>已下架</option>
                    </select>
                </form>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>場地名稱</th>
                        <th class="w-50">描述</th>
                        <th>預約狀態</th>
                        <th>操作</th>
                        <th>狀態</th>
                        <th>移除場地</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['description']) ?></td>
                                <td>
                                    <?= $row['usage_status'] ?>
                                    <a href="booking-create.php?venue_id=<?= $row['id'] ?>" class="btn btn-primary mx-2">
                                        預約
                                    </a>
                                </td>
                                <td>
                                    <a href="venue-edit.php?id=<?= $row['id'] ?>" class="btn btn-primary">編輯</a>
                                    <a href="venue-handle-restore.php?id=<?= $row['id'] ?>" class="btn btn-warning">上架</a>
                                    <a href="venue-handle-delete.php?id=<?= $row['id'] ?>" class="btn btn-warning">下架</a>
                                </td>
                                <td>
                                    <?= $row['status'] == 1 ? '<span class="text-success">上架</span>' : '<span class="text-primary">下架</span>' ?>
                                </td>
                                <td>
                                    <a href="venue-handle-hard-delete.php?id=<?= $row['id'] ?>" class="btn btn-danger">刪除</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">目前沒有上架的場地</td>
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