<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("../ceramics_db_connect.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM exhibition WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $exhibition = mysqli_fetch_assoc($result);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $tag_id = $_POST['tag_id'];

    $sql = "UPDATE exhibition SET title = '$title', description = '$description', start_date = '$start_date', end_date = '$end_date', tag_id = '$tag_id' WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        echo "展覽已成功更新！";
    } else {
        echo "錯誤: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>編輯展覽</title>
    <?php include("../css.php"); ?>
    <?php include("../ev-css.php"); ?>
</head>

<body>
    <?php include("../aside.php"); ?>
    <main class="main-content position-relative max-height-vh-100 border-radius-lg">
        <?php include("../navbar.php"); ?>

        <div class="container-fluid">
            <h2>編輯展覽</h2>
            <form action="exhibition-edit.php?id=<?= $exhibition['id'] ?>" method="POST">
                <div class="mb-3">
                    <label for="title" class="form-label">展覽名稱</label>
                    <input type="text" name="title" class="form-control" id="title" value="<?= htmlspecialchars($exhibition['title']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">描述</label>
                    <textarea name="description" class="form-control" id="description" required><?= htmlspecialchars($exhibition['description']) ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="start_date" class="form-label">開始日期</label>
                    <input type="date" name="start_date" class="form-control" id="start_date" value="<?= $exhibition['start_date'] ?>" required>
                </div>
                <div class="mb-3">
                    <label for="end_date" class="form-label">結束日期</label>
                    <input type="date" name="end_date" class="form-control" id="end_date" value="<?= $exhibition['end_date'] ?>" required>
                </div>
                <div class="mb-3">
                    <label for="tag_id" class="form-label">標籤 ID</label>
                    <input type="number" name="tag_id" class="form-control" id="tag_id" value="<?= $exhibition['tag_id'] ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">更新</button>
            </form>
        </div>

        <?php include("../footer.php"); ?>
    </main>
    <?php include("../js.php"); ?>
</body>

</html>

