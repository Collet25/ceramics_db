<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php
require_once("../ceramics_db_connect.php");

$venue_id = isset($_GET['venue_id']) ? $_GET['venue_id'] : null;

if (!$venue_id) {
    echo "未指定場地";
    exit;
}

// 查詢場地資料
$sql = "SELECT * FROM venue WHERE id = '$venue_id'";
$result = $conn->query($sql);
$venue = $result->fetch_assoc();

if (!$venue) {
    echo "場地不存在";
    exit;
}
?>


<?php
require_once("../ceramics_db_connect.php");

$sql_venues = "SELECT * FROM venue WHERE status = 1";
$result_venues = $conn->query($sql_venues);

$sql_users = "SELECT * FROM users";
$result_users = $conn->query($sql_users);
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../logo-img/head-icon.png">
    <title>新增預約</title>
    <?php include("../css.php"); ?>
    <?php include("../ev-css.php"); ?>
</head>

<body>
    <?php include("../aside.php"); ?>
    <main class="main-content position-relative max-height-vh-100 border-radius-lg">
        <?php include("../navbar.php"); ?>

        <div class="container px-5 py-3">
            <h2 class="mb-5">新增預約</h2>
            <form action="booking-handle.php" method="POST">
                <div class="mb-3">
                    <h3 class="mb-4">預約場地 — <?= htmlspecialchars($venue['name']) ?></h3>
                    <input type="hidden" name="venue_id" value="<?= $venue_id ?>">

                    <label for="venue_id" class="form-label">場地</label>
                    <select id="venue_id" name="venue_id" class="form-select" required>
                        <?php while ($venue = $result_venues->fetch_assoc()): ?>
                            <option value="<?= $venue['id'] ?>"><?= htmlspecialchars($venue['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="user_id" class="form-label">預約者</label>
                    <select id="user_id" name="user_id" class="form-select" required>
                        <?php while ($user = $result_users->fetch_assoc()): ?>
                            <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="start_time" class="form-label">開始時間</label>
                    <input type="datetime-local" id="start_time" name="start_time" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="end_time" class="form-label">結束時間</label>
                    <input type="datetime-local" id="end_time" name="end_time" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">新增預約</button>
            </form>
        </div>
        <?php include("../footer.php"); ?>
    </main>
    <?php include("../js.php"); ?>
</body>

</html>