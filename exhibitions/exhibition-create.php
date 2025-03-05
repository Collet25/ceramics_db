<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("../ceramics_db_connect.php");

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
    $image = "";
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
        // 新增展覽資料
        $sql = "INSERT INTO exhibition (title, image, category_id, description, start_date, end_date, tag_id)
                VALUES ('$title', '$image', $category_id, '$description', '$start_date', '$end_date', $tag_id)";
        
        if ($conn->query($sql) === TRUE) {
            $exhibition_id = $conn->insert_id; // 取得新增的展覽 ID

            // 插入展覽與展覽廳的關聯，並自動新增預約資料
            if (!empty($venue_ids)) {
                foreach ($venue_ids as $venue_id) {
                    $venue_id = (int)$venue_id;

                    // 新增展覽與展覽廳的關聯
                    $conn->query("INSERT INTO exhibition_venue (exhibition_id, venue_id) VALUES ($exhibition_id, $venue_id)");

                    // 自動新增預約資料
                    $conn->query("INSERT INTO bookings (venue_id, exhibition_id, start_time, end_time, status)
                                  VALUES ($venue_id, $exhibition_id, '$start_date', '$end_date', 'pending')");
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
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../logo-img/head-icon.png">
    <title>新增展覽</title>
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
                        $result = $conn->query("SELECT * FROM exhibition_category");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="venue_ids">選擇展覽廳 (可多選)</label>
                    <select name="venue_ids[]" id="venue_ids" class="form-select" multiple>
                        <?php
                        while ($venue = $venue_result->fetch_assoc()) {
                            echo "<option value='{$venue['id']}'>{$venue['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="start_date" class="form-label">開始日期</label>
                    <input type="date" name="start_date" class="form-control" id="start_date" required>
                </div>
                <div class="mb-3">
                    <label for="end_date" class="form-label">結束日期</label>
                    <input type="date" name="end_date" class="form-control" id="end_date" required>
                </div>
                <div class="mb-3">
                    <label for="tag_id" class="form-label">標籤</label>
                    <select class="form-select" id="tag_id" name="tag_id" required>
                        <?php
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
