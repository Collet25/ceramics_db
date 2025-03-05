<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
require_once("../ceramics_db_connect.php");

// 獲取篩選條件，預設為全部
$filter = isset($_GET['filter']) ? mysqli_real_escape_string($conn, $_GET['filter']) : 'all';
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : 'all';
$where = [];

// 根據篩選條件建立 SQL 查詢
switch ($filter) {
    case 'active':
        $where[] = "NOW() BETWEEN exhibition.start_date AND exhibition.end_date";
        break;
    case 'inactive':
        $where[] = "NOW() > exhibition.end_date";
        break;
    case 'upcoming':
        $where[] = "exhibition.start_date > NOW()";
        break;
}

// 根據分類條件建立 SQL 查詢
switch ($category) {
    case 'permanent':
        $where[] = "exhibition.category_id = 2";
        break;
    case 'special':
        $where[] = "exhibition.category_id = 3";
        break;
}

$where_sql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

// 分頁設定
$perPage = 5; // 每頁顯示 5 筆
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $perPage;

// 總資料數量
$totalSql = "SELECT COUNT(DISTINCT exhibition.id) AS total FROM exhibition 
             LEFT JOIN exhibition_venue ON exhibition.id = exhibition_venue.exhibition_id
             LEFT JOIN venue ON exhibition_venue.venue_id = venue.id
             $where_sql";
$totalResult = mysqli_query($conn, $totalSql);
$totalRow = mysqli_fetch_assoc($totalResult);
$total = $totalRow['total'];
$totalPages = ceil($total / $perPage);

// 連接 exhibition_category 表進行篩選
$sql = "SELECT exhibition.*, exhibition_category.name AS category_name, GROUP_CONCAT(venue.name SEPARATOR ', ') AS venue_names
        FROM exhibition 
        LEFT JOIN exhibition_category ON exhibition.category_id = exhibition_category.id
        LEFT JOIN exhibition_venue ON exhibition.id = exhibition_venue.exhibition_id
        LEFT JOIN venue ON exhibition_venue.venue_id = venue.id
        $where_sql
        GROUP BY exhibition.id
        LIMIT $perPage OFFSET $offset";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../logo-img/head-icon.png">
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
                        <th>展覽廳</th>
                        <th>描述</th>
                        <th>開始日期</th>
                        <th>結束日期</th>
                        <th>狀態</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <?php
                        $now = date('Y-m-d H:i:s');
                        $status = ($now >= $row['start_date'] && $now <= $row['end_date']) ? '展覽中' : (($now > $row['end_date']) ? '已結束' : '未開始');
                        $image = !empty($row['image']) ? '../uploads/exhibition/' . htmlspecialchars($row['image']) : '../uploads/default.jpg';
                        ?>
                        <tr>
                            <td><img src="<?= $image ?>" alt="展覽圖片" class="thumbnail"></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= htmlspecialchars($row['venue_names'] ?? '') ?></td>
                            <td><?= htmlspecialchars(mb_substr($row['description'], 0, 20)) . (mb_strlen($row['description']) > 20 ? '...' : '') ?></td>
                            <td><?= date('Y-m-d', strtotime($row['start_date'])) ?></td>
                            <td><?= date('Y-m-d', strtotime($row['end_date'])) ?></td>
                            <td><?= $status ?></td>
                            <td>
                                <a href="exhibition-edit.php?id=<?= $row['id'] ?>" class="btn btn-primary">編輯</a>
                                <a href="exhibition-handle-delete.php?id=<?= $row['id'] ?>" class="btn btn-danger">刪除</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- 分頁按鈕 -->
            <div class="d-flex justify-content-center mt-4">
                <nav>
                    <ul class="pagination">
                        <?php if ($page > 1): ?>
                            <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>&filter=<?= $filter ?>&category=<?= $category ?>">上一頁</a></li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&filter=<?= $filter ?>&category=<?= $category ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <?php if ($page < $totalPages): ?>
                            <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>&filter=<?= $filter ?>&category=<?= $category ?>">下一頁</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>

        <?php include("../footer.php"); ?>
    </main>
    <?php include("../js.php"); ?>
</body>

</html>
