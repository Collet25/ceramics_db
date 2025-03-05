<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("../ceramics_db_connect.php");

// 獲取展覽資料
$exhibition_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($exhibition_id <= 0) {
    die("無效的展覽 ID");
}

$exhibition_sql = "SELECT * FROM exhibition WHERE id = $exhibition_id";
$exhibition_result = $conn->query($exhibition_sql);
$exhibition = $exhibition_result->fetch_assoc();

if (!$exhibition) {
    die("找不到指定的展覽");
}

// 獲取展覽廳資料
$venue_sql = "SELECT id, name FROM venue WHERE status = 1";
$venue_result = $conn->query($venue_sql);

// 處理表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = isset($_POST['title']) ? mysqli_real_escape_string($conn, $_POST['title']) : '';
    $category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
    $description = isset($_POST['description']) ? mysqli_real_escape_string($conn, $_POST['description']) : '';
    $start_date = isset($_POST['start_date']) ? mysqli_real_escape_string($conn, $_POST['start_date']) : '';
    $end_date = isset($_POST['end_date']) ? mysqli_real_escape_string($conn, $_POST['end_date']) : '';
    $tag_id = isset($_POST['tag_id']) ? (int)$_POST['tag_id'] : 0;
    $venue_ids = isset($_POST['venue_ids']) ? $_POST['venue_ids'] : [];

    // 處理圖片上傳
    $image = $exhibition['image']; // 預設為現有圖片
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = uniqid() . "_" . $_FILES['image']['name'];
        $imagePath = "../uploads/exhibition/" . basename($imageName);

        if (is_writable("../uploads/exhibition/") && move_uploaded_file($imageTmpPath, $imagePath)) {
            $image = basename($imageName);
        } else {
            echo "圖片上傳失敗，請檢查 uploads 目錄的寫入權限";
        }
    }

    if ($category_id > 0 && !empty($title) && !empty($start_date) && !empty($end_date)) {
        // 更新展覽資料
        $sql = "UPDATE exhibition 
                SET title = '$title', image = '$image', category_id = $category_id, description = '$description', 
                    start_date = '$start_date', end_date = '$end_date', tag_id = $tag_id
                WHERE id = $exhibition_id";

        if ($conn->query($sql) === TRUE) {
            // 清除舊的展覽與展覽廳關聯
            $conn->query("DELETE FROM exhibition_venue WHERE exhibition_id = $exhibition_id");

            // 插入新的展覽與展覽廳的關聯
            if (!empty($venue_ids)) {
                foreach ($venue_ids as $venue_id) {
                    $venue_id = (int)$venue_id;
                    $conn->query("INSERT INTO exhibition_venue (exhibition_id, venue_id) VALUES ($exhibition_id, $venue_id)");
                }
            }

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
    <title>編輯展覽</title>
    <?php include("../css.php"); ?>
    <?php include("../ev-css.php"); ?>
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
            <h2 class="mb-5">編輯展覽</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">展覽名稱</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($exhibition['title']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">展覽圖片</label>
                    <input type="file" class="form-control" id="image" name="image">
                    <?php if ($exhibition['image']) : ?>
                        <img src="../uploads/exhibition/<?php echo $exhibition['image']; ?>" alt="Current Image" class="mt-3" width="200">
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="category_id" class="form-label">類別</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">請選擇類別</option>
                        <?php
                        $result = $conn->query("SELECT * FROM exhibition_category");
                        while ($row = $result->fetch_assoc()) {
                            $selected = $row['id'] == $exhibition['category_id'] ? "selected" : "";
                            echo "<option value='" . $row['id'] . "' $selected>" . $row['name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="venue_ids">選擇展覽廳 (可多選)</label>
                    <select name="venue_ids[]" id="venue_ids" class="form-select" multiple>
                        <?php
                        $venue_ids_sql = "SELECT venue_id FROM exhibition_venue WHERE exhibition_id = $exhibition_id";
                        $venue_ids_result = $conn->query($venue_ids_sql);
                        $selected_venue_ids = [];
                        while ($row = $venue_ids_result->fetch_assoc()) {
                            $selected_venue_ids[] = $row['venue_id'];
                        }

                        while ($venue = $venue_result->fetch_assoc()) {
                            $selected = in_array($venue['id'], $selected_venue_ids) ? "selected" : "";
                            echo "<option value='{$venue['id']}' $selected>{$venue['name']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">描述</label>
                    <textarea class="form-control" id="description" name="description"><?php echo htmlspecialchars($exhibition['description']); ?></textarea>

                <div class="mb-3">
                    <label for="start_date" class="form-label">開始日期</label>
                    <input type="date" name="start_date" class="form-control" id="start_date" value="<?php echo date('Y-m-d', strtotime($exhibition['start_date'])); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="end_date" class="form-label">結束日期</label>
                    <input type="date" name="end_date" class="form-control" id="end_date" value="<?php echo date('Y-m-d', strtotime($exhibition['end_date'])); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="tag_id" class="form-label">標籤</label>
                    <select class="form-select" id="tag_id" name="tag_id" required>
                        <?php
                        $result = $conn->query("SELECT * FROM tags");
                        while ($row = $result->fetch_assoc()) {
                            $selected = $row['id'] == $exhibition['tag_id'] ? "selected" : "";
                            echo "<option value='" . $row['id'] . "' $selected>" . $row['name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">更新展覽</button>
            </form>
        </div>

        <?php include("../footer.php"); ?>
    </main>
    <?php include("../js.php"); ?>
</body>

</html>