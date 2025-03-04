<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("../ceramics_db_connect.php");

// 獲取篩選條件，預設為全部
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$where = [];

// 根據篩選條件建立 SQL 查詢
switch ($filter) {
    case 'active':  // 展覽中
        $where[] = "NOW() BETWEEN exhibition.start_date AND exhibition.end_date";
        break;
    case 'inactive':  // 已結束
        $where[] = "NOW() > exhibition.end_date";
        break;
    case 'upcoming':  // 未開始
        $where[] = "exhibition.start_date > NOW()";
        break;
}

// 根據分類條件建立 SQL 查詢
switch ($category) {
    case 'permanent':  // 常設展 (exhibition_category_id = 2)
        $where[] = "exhibition.category_id = 2";
        break;
    case 'special':  // 特展 (exhibition_category_id = 3)
        $where[] = "exhibition.category_id = 3";
        break;
    default:  // 全部展覽
        break;
}

$where_sql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

// 連接 exhibition_category 表進行篩選
$sql = "SELECT exhibition.*, exhibition_category.name AS category_name 
        FROM exhibition 
        LEFT JOIN exhibition_category ON exhibition.category_id = exhibition_category.id
        $where_sql";

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

        <!-- 展覽分類 -->
        <div class="m-auto w-70">
            <form class="row mt-3 mx-2 text-center" method="GET">
                <div class="col">
                    <a href="?category=all" class="fil <?= $category === 'all' ? 'active' : '' ?>">全部展覽</a>
                </div>
                <div class="col">
                    <a href="?category=permanent" class="fil <?= $category === 'permanent' ? 'active' : '' ?>">常設展</a>
                </div>
                <div class="col">
                    <a href="?category=special" class="fil <?= $category === 'special' ? 'active' : '' ?>">特展</a>
                </div>
            </form>
            <hr>
        </div>

        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>展覽列表</h2>
                <form method="GET" class="d-inline-block">
                    <select name="filter" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="all" <?= $filter === 'all' ? 'selected' : '' ?>>全部</option>
                        <option value="active" <?= $filter === 'active' ? 'selected' : '' ?>>展覽中</option>
                        <option value="inactive" <?= $filter === 'inactive' ? 'selected' : '' ?>>已結束</option>
                        <option value="upcoming" <?= $filter === 'upcoming' ? 'selected' : '' ?>>未開始</option>
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
                            $now = date('Y-m-d H:i:s');
                            if ($now >= $row['start_date'] && $now <= $row['end_date']) {
                                $status = '展覽中';
                            } elseif ($now > $row['end_date']) {
                                $status = '已結束';
                            } else {
                                $status = '未開始';
                            }

                            $start_date = date('Y-m-d', strtotime($row['start_date']));
                            $end_date = date('Y-m-d', strtotime($row['end_date']));
                            $image = !empty($row['image']) ? htmlspecialchars($row['image']) : '../uploads/default.jpg';
                            ?>
                            <tr class="exhibition-item">
                                <td><img src="<?= $image ?>" alt="展覽圖片" class="thumbnail"></td>
                                <td><?= htmlspecialchars($row['title']) ?></td>
                                <td class="text-wrap"><?= htmlspecialchars(mb_substr($row['description'], 0, 20)) . (mb_strlen($row['description']) > 20 ? '...' : '') ?></td>
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

    <!-- JavaScript 部分 -->
    <script>
        window.onload = function() {
            // 獲取所有展覽項目
            const exhibitions = document.querySelectorAll('.exhibition-item');

            exhibitions.forEach(exhibition => {
                const descriptionCell = exhibition.querySelector('.text-wrap');
                let description = descriptionCell.textContent;

                // 限制描述顯示為前 20 個字
                if (description.length > 20) {
                    description = description.substring(0, 60) + '...';
                }

                // 更新描述
                descriptionCell.textContent = description;
            });
        };
    </script>
</body>

</html>