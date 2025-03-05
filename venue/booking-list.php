<?php
require_once("../ceramics_db_connect.php");

$sql = "SELECT b.*, v.name AS venue_name, u.name AS user_name 
        FROM bookings b
        JOIN venue v ON b.venue_id = v.id
        JOIN users u ON b.user_id = u.id";
$result = $conn->query($sql);
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
            <table class="table">
                <thead>
                    <tr>
                        <th>場地名稱</th>
                        <th>預約者</th>
                        <th>開始時間</th>
                        <th>結束時間</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['venue_name']) ?></td>
                                <td><?= htmlspecialchars($row['user_name']) ?></td>
                                <td><?= htmlspecialchars($row['start_time']) ?></td>
                                <td><?= htmlspecialchars($row['end_time']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">目前沒有預約資料</td>
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