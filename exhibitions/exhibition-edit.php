<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("../ceramics_db_connect.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM exhibition WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $exhibition = mysqli_fetch_assoc($result);

    if (!$exhibition) {  // 若找不到展覽
        echo "找不到該展覽";
        exit;
    }

    // 預先查詢分類與標籤
    $category_result = $conn->query("SELECT * FROM exhibition_category");
    $tag_result = $conn->query("SELECT * FROM tags");
} else {
    echo "未指定展覽 ID";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $category_id = intval($_POST['category_id']);
    $tag_id = intval($_POST['tag_id']);
    $image_sql = "";

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageName = basename($_FILES['image']['name']);
        $targetDir = "../uploads/exhibition/";
        $targetFile = $targetDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image_sql = ", image = '$imageName'";
        } else {
            echo "圖片上傳失敗";
        }
    }

    $sql = "UPDATE exhibition SET title = '$title', category_id = $category_id, description = '$description', 
            start_date = '$start_date', end_date = '$end_date', tag_id = $tag_id $image_sql WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        header("Location: exhibition-list.php");
        exit;
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
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../logo-img/head-icon.png">
    <title>編輯展覽</title>
    <?php include("../css.php"); ?>
    <?php include("../ev-css.php"); ?>
</head>

<body>
    <?php include("../aside.php"); ?>
    <main class="main-content position-relative max-height-vh-100 border-radius-lg">
        <?php include("../navbar.php"); ?>

        <div class="container-fluid w-80 my-2">
            <h2>編輯展覽</h2>
            <form action="exhibition-edit.php?id=<?= $exhibition['id'] ?>" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">展覽名稱</label>
                    <input type="text" name="title" class="form-control" id="title" value="<?= htmlspecialchars($exhibition['title']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">展覽圖片</label>
                    <input type="file" name="image" class="form-control" id="image">
                    <?php if (!empty($exhibition['image'])): ?>
                        <p class="my-3">——— 目前圖片 ———</p>
                        <img src="../uploads/exhibition/<?= htmlspecialchars($exhibition['image']) ?>" alt="展覽圖片" style="max-width: 500px;">
                    <?php endif; ?>


                </div>
                <div class="mb-3">
                    <label for="category_id" class="form-label">類別</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">請選擇類別</option>
                        <?php
                        // 獲取所有展覽類別
                        $result = $conn->query("SELECT * FROM exhibition_category");
                        while ($row = $result->fetch_assoc()) {
                            $selected = $row['id'] == $exhibition['category_id'] ? 'selected' : '';
                            echo "<option value='" . $row['id'] . "' $selected>" . $row['name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">描述</label>
                    <textarea name="description" class="form-control" id="description" required><?= htmlspecialchars($exhibition['description']) ?></textarea>
                </div>
                <label for="venue_ids">選擇展覽廳 (可多選)</label>
                <select name="venue_ids[]" id="venue_ids" class="form-select" multiple>
                    <?php
                    $venueResult = $conn->query("SELECT * FROM venue WHERE status = 1");
                    while ($venue = $venueResult->fetch_assoc()) {
                        echo "<option value='{$venue['id']}'>{$venue['name']}</option>";
                    }
                    ?>
                </select>

                <div class="mb-3">
                    <label for="start_date" class="form-label">開始日期</label>
                    <input type="date" name="start_date" class="form-control" id="start_date"
                        value="<?= substr($exhibition['start_date'], 0, 10) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="end_date" class="form-label">結束日期</label>
                    <input type="date" name="end_date" class="form-control" id="end_date"
                        value="<?= substr($exhibition['end_date'], 0, 10) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="tag_id" class="form-label">標籤</label>
                    <select class="form-select" id="tag_id" name="tag_id" required>
                        <option value="">請選擇標籤</option>
                        <?php
                        // 獲取所有標籤
                        $result = $conn->query("SELECT * FROM tags");
                        while ($row = $result->fetch_assoc()) {
                            $selected = $row['id'] == $exhibition['tag_id'] ? 'selected' : '';
                            echo "<option value='" . $row['id'] . "' $selected>" . $row['name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">更新</button>
            </form>
        </div>

        <?php include("../footer.php"); ?>
    </main>
    <?php include("../js.php"); ?>
</body>

</html>