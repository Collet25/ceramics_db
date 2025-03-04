<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("../ceramics_db_connect.php");

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "SELECT * FROM exhibition WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $exhibition = mysqli_fetch_assoc($result);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $category_id = (int)$_POST['category_id'];  // ✅ 新增 category_id
    $tag_id = (int)$_POST['tag_id'];
    $image_sql = "";  // 用於儲存圖片更新的 SQL 字串

    // ✅ 處理圖片上傳
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageName = basename($_FILES['image']['name']);
        $targetDir = "../exhibition/";  // 📌 統一圖片上傳目錄
        $targetFile = $targetDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image_sql = ", image = '$targetFile'";  // 🔄 更新圖片路徑
        } else {
            echo "圖片上傳失敗";
        }
    }

    // ✅ 更新資料庫，包括 category_id
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
            <form action="exhibition-edit.php?id=<?= $exhibition['id'] ?>" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">展覽名稱</label>
                    <input type="text" name="title" class="form-control" id="title" value="<?= htmlspecialchars($exhibition['title']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">展覽圖片</label>
                    <input type="file" name="image" class="form-control" id="image">
                    <?php if (!empty($exhibition['image'])): ?>
                        <img src="<?= $exhibition['image'] ?>" alt="展覽圖片" style="max-width: 200px; margin-top: 10px;">
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
