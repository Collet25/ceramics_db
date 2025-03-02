<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("../ceramics_db_connect.php");

// 獲取篩選條件，預設為全部
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$where = [];

// 根據篩選條件建立 SQL 查詢
switch ($filter) {
    case 'active':  // 展覽中
        $where[] = "NOW() BETWEEN start_date AND end_date";
        break;
    case 'inactive':  // 已結束
        $where[] = "NOW() > end_date";
        break;
}

$where_sql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
$sql = "SELECT * FROM exhibition $where_sql";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>展覽列表</title>
    <?php include("../css.php"); ?>
    <?php include("../ev-css.php"); ?>
    <style>
        .thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <?php include("../aside.php"); ?>
    <main class="main-content position-relative max-height-vh-100 border-radius-lg">
        <?php include("../navbar.php"); ?>

        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>展覽列表</h2>
                <form method="GET" class="d-inline-block">
                    <select name="filter" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="all" <?= $filter === 'all' ? 'selected' : '' ?>>全部</option>
                        <option value="active" <?= $filter === 'active' ? 'selected' : '' ?>>展覽中</option>
                        <option value="inactive" <?= $filter === 'inactive' ? 'selected' : '' ?>>已結束</option>
                    </select>
                </form>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>圖片</th>
                        <th>名稱</th>
                        <th>描述</th>
                        <th>開始日期</th>
                        <th>結束日期</th>
                        <th>狀態</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <?php
                            // 動態判斷展覽狀態
                            $now = date('Y-m-d H:i:s');
                            if ($now >= $row['start_date'] && $now <= $row['end_date']) {
                                $status = '展覽中';
                            } elseif ($now > $row['end_date']) {
                                $status = '已結束';
                            } else {
                                $status = '未開始';
                            }

                            // 簡化日期格式 (只顯示年月日)
                            $start_date = date('Y-m-d', strtotime($row['start_date']));
                            $end_date = date('Y-m-d', strtotime($row['end_date']));

                            // 處理圖片顯示 (如果沒有圖片則顯示預設圖片)
                            $image = !empty($row['image']) ? htmlspecialchars($row['image']) : '../uploads/default.jpg';
                            ?>
                            <tr>
                                <td><img src="<?= $image ?>" alt="展覽圖片" class="thumbnail"></td>
                                <td><?= htmlspecialchars($row['title']) ?></td>
                                <td class="text-wrap"><?= htmlspecialchars($row['description']) ?></td>
                                <td><?= $start_date ?></td>
                                <td><?= $end_date ?></td>
                                <td><?= $status ?></td>
                                <td>
                                    <a href="exhibition-edit.php?id=<?= $row['id'] ?>" class="btn btn-primary">編輯</a>
                                    <a href="exhibition-handle-delete.php?id=<?= $row['id'] ?>" class="btn btn-danger">刪除</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">目前沒有任何展覽</td>
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
