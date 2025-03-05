<?php
require_once("../ceramics_db_connect.php");

// 新增類別處理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_name'])) {
    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
    if (!empty($category_name)) {
        $sql = "INSERT INTO exhibition_category (name) VALUES ('$category_name')";
        if ($conn->query($sql) === TRUE) {
            echo "類別新增成功!";
        } else {
            echo "錯誤: " . $conn->error;
        }
    } else {
        echo "類別名稱不能為空";
    }
}

// 取得所有類別
$result = $conn->query("SELECT * FROM exhibition_category");
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../logo-img/head-icon.png">
    <title>管理展覽類別</title>
    <?php include("../css.php"); ?>
</head>

<body>
    <?php include("../aside.php"); ?>
    <main class="main-content position-relative max-height-vh-100 border-radius-lg">
        <?php include("../navbar.php"); ?>

        <div class="container">
            <h2 class="mb-5">管理展覽類別</h2>

            <!-- 新增類別表單 -->
            <form method="POST" class="mb-4">
                <div class="mb-3">
                    <label for="category_name" class="form-label">新增類別名稱</label>
                    <input type="text" class="form-control" id="category_name" name="category_name" required>
                </div>
                <button type="submit" class="btn btn-primary">新增類別</button>
            </form>

            <!-- 顯示所有類別 -->
            <h3>所有類別</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>類別名稱</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <?php include("../footer.php"); ?>
    </main>
    <?php include("../js.php"); ?>
</body>

</html>