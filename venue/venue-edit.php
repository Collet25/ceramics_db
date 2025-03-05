<!-- 當使用者點擊編輯按鈕後，會跳到這個頁面顯示原本的資料，讓他可以修改。 -->

<?php
require_once("../ceramics_db_connect.php");

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "SELECT * FROM venue WHERE id = $id";
    $result = $conn->query($sql);
    $venue = $result->fetch_assoc();
} else {
    header("Location: venue-list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../logo-img/head-icon.png">
    <title>編輯場地</title>
    <?php include("../css.php"); ?>
    <?php include("../ev-css.php"); ?>
</head>

<body>
    <?php include("../aside.php"); ?>
    <main class="main-content">
        <?php include("../navbar.php"); ?>
        <div class="container mt-4">
            <h2>編輯場地</h2>
            <form action="venue-handle-edit.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $venue['id'] ?>">
                <div class="mb-3">
                    <label for="name" class="form-label">場地名稱</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= $venue['name'] ?>" required>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">場地圖片</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    <img src="../uploads/venues/<?= $venue["image"] ?>" class="img-thumbnail mt-3" style="max-width: 200px;">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">場地描述</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?= $venue['description'] ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">更新場地</button>
                <a href="venue-list.php" class="btn btn-secondary">取消</a>
            </form>
        </div>
        <?php include("../footer.php"); ?>

    </main>
    <?php include("../js.php"); ?>
</body>

</html>