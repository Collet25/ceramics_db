<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("../ceramics_db_connect.php");

// 處理表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 確保取得並處理 POST 參數
    $title = isset($_POST['title']) ? mysqli_real_escape_string($conn, $_POST['title']) : '';
    $category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0; // 改為 category_id 並轉為整數
    $description = isset($_POST['description']) ? mysqli_real_escape_string($conn, $_POST['description']) : '';
    $start_date = isset($_POST['start_date']) ? mysqli_real_escape_string($conn, $_POST['start_date']) : '';
    $end_date = isset($_POST['end_date']) ? mysqli_real_escape_string($conn, $_POST['end_date']) : '';
    $tag_id = isset($_POST['tag_id']) ? (int)$_POST['tag_id'] : 0;

    // 處理圖片上傳
    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = $_FILES['image']['name'];
        $imagePath = "../uploads/" . basename($imageName);

        // 確保 uploads 目錄存在並有適當的權限
        if (is_writable("../uploads/") && move_uploaded_file($imageTmpPath, $imagePath)) {
            $image = $imagePath;  // 儲存圖片路徑
        } else {
            echo "圖片上傳失敗，請檢查 uploads 目錄的寫入權限";
        }
    }

    // 插入資料庫，並確保所有欄位都正確
    if ($category_id > 0 && !empty($title) && !empty($start_date) && !empty($end_date)) {
        $sql = "INSERT INTO exhibition (title, image, category_id, description, start_date, end_date, tag_id)
                VALUES ('$title', '$image', $category_id, '$description', '$start_date', '$end_date', $tag_id)";

        if ($conn->query($sql) === TRUE) {
            header("Location: exhibition-list.php");
            exit;
        } else {
            echo "錯誤: " . $conn->error;
        }
    } else {
        echo "請填寫所有必填欄位，並選擇有效的類別";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新增展覽</title>
    <?php include("../css.php"); ?>
    <style>
        body {
            margin: 50px;
        }

        .card {
            width: 100%;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }
    </style>
</head>

<body>
    <?php include("../aside.php"); ?>
    <main class="main-content position-relative max-height-vh-100 border-radius-lg">
        <?php include("../navbar.php"); ?>

        <div class="container">
            <h2 class="mb-5">新增展覽</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">展覽名稱</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">展覽圖片</label>
                    <input type="file" class="form-control" id="image" name="image">
                </div>
                <div class="mb-3">
                    <label for="category_id" class="form-label">類別</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">請選擇類別</option>
                        <?php
                        // 獲取所有展覽類別
                        $result = $conn->query("SELECT * FROM exhibition_category");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                        }
                        ?>
                    </select>

                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">描述</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="start_date" class="form-label">開始日期</label>
                    <input type="date" name="start_date" class="form-control" id="start_date"
                        value="<?= isset($exhibition['start_date']) ? substr($exhibition['start_date'], 0, 10) : '' ?>" required>
                </div>
                <div class="mb-3">
                    <label for="end_date" class="form-label">結束日期</label>
                    <input type="date" name="end_date" class="form-control" id="end_date"
                        value="<?= isset($exhibition['end_date']) ? substr($exhibition['end_date'], 0, 10) : '' ?>" required>
                </div>
                <div class="mb-3">
                    <label for="tag_id" class="form-label">標籤</label>
                    <select class="form-select" id="tag_id" name="tag_id" required>
                        <?php
                        // 獲取所有標籤
                        $result = $conn->query("SELECT * FROM tags");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">新增展覽</button>
            </form>
        </div>

        <?php include("../footer.php"); ?>
    </main>
    <?php include("../js.php"); ?>
</body>

</html>