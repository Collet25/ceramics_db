<?php
require_once("../ceramics_db_connect.php");

// 分頁設定
$page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// 計算總筆數
$totalSql = "SELECT COUNT(*) as total FROM products WHERE deleted_at IS NOT NULL";
$totalResult = $conn->query($totalSql);
$totalRow = $totalResult->fetch_assoc();
$totalRows = $totalRow["total"];
$totalPages = ceil($totalRows / $per_page);

// 查詢已刪除的商品
$sql = "SELECT p.*, c.name AS category_name, s.name AS subcategory_name, 
        m.name AS material_name, o.name AS origin_name
        FROM products p
        LEFT JOIN categories c ON p.category = c.name
        LEFT JOIN subcategories s ON p.subcategory = s.name
        LEFT JOIN materials m ON p.material = m.name
        LEFT JOIN origins o ON p.origin = o.name
        WHERE p.deleted_at IS NOT NULL
        ORDER BY p.deleted_at DESC
        LIMIT ?, ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $offset, $per_page);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>已刪除商品</title>
    <?php include("../css.php"); ?>
    <link rel="stylesheet" href="../products/style_p.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .head-icon {
            width: 30px;
            height: 30px;
        }

        /* 按鈕樣式 */
        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            margin: 0 2px;
        }

        .btn-icon i {
            font-size: 1.1rem;
        }

        /* 操作按鈕容器 */
        .action-buttons {
            white-space: nowrap;
            text-align: center;
        }

        /* 表格樣式 */
        .table th {
            text-align: center;          /* 表頭文字置中 */
            vertical-align: middle;       /* 垂直置中 */
            background-color: var(--bg-header);  /* 使用深磚紅背景 */
            color: var(--text-light);     /* 使用淺色文字 */
            font-weight: 500;
            font-size: 0.95rem;
            padding: 1.25rem 1rem;
            border: none;
        }

        .table td {
            vertical-align: middle;
            text-align: center;
        }

        .table td.text-start,
        .table th.text-start {           /* 允許特定欄位靠左對齊 */
            text-align: left;
        }

        .product-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .product-name {
            text-align: left;
            font-weight: 500;
        }

        .product-price {
            font-weight: 500;
        }
    </style>
</head>

<body class="g-sidenav-show">
    <?php include("../aside.php"); ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <?php include("../navbar.php"); ?>

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="mb-4">
                        <div class="container">
                            <div class="page-header">
                                <div>
                                    <a href="product-list.php" class="btn btn-outline-secondary">
                                        <i class="bi bi-chevron-double-left"></i> 
                                    </a>
                                </div>
                                <h1 class="page-title ms-2">已刪除商品</h1>
                            </div>

                            <div class="mb-3">
                                
                                <div class="text-end mx-3">
                                    共 <?= $totalRows ?> 筆已刪除商品
                                </div>
                                
                            </div>

                            <!-- 商品列表 -->
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>圖片</th>
                                            <th class="text-start">商品名稱</th>
                                            <th>分類</th>
                                            <th>價格</th>
                                            <th>刪除時間</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= $row["id"] ?></td>
                                            <td>
                                                <img src="../uploads/<?= $row["image"] ?>" 
                                                    alt="<?= $row["name"] ?>" 
                                                    class="product-img">
                                            </td>
                                            <td class="product-name"><?= $row["name"] ?></td>
                                            <td><?= $row["category_name"] ?></td>
                                            <td class="product-price">NT$ <?= number_format($row["price"]) ?></td>
                                            <td><?= date("Y/m/d H:i", strtotime($row["deleted_at"])) ?></td>
                                            <td class="action-buttons">
                                                <button class="btn btn-icon btn-outline-success restore-btn"
                                                    data-id="<?= $row["id"] ?>"
                                                    title="還原">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>
                                                <button class="btn btn-icon btn-outline-danger delete-permanent-btn"
                                                    data-id="<?= $row["id"] ?>"
                                                    title="永久刪除">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- 分頁 -->
                            <?php if ($totalPages > 1): ?>
                            <nav aria-label="Page navigation" class="mt-4">
                                <ul class="pagination justify-content-center">
                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= $i == $page ? "active" : "" ?>">
                                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                    <?php endfor; ?>
                                </ul>
                            </nav>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include("../footer.php"); ?>
    </main>

    <?php include("../js.php"); ?>

    <script>
    $(document).ready(function() {
        // 還原商品
        $(".restore-btn").click(function() {
            let id = $(this).data("id");
            if (confirm("確定要還原這個商品嗎？")) {
                $.ajax({
                    url: "product-restore.php",
                    method: "POST",
                    data: { id: id },
                    dataType: "json",
                    success: function(response) {
                        console.log('Response:', response);
                        if (response.success) {
                            alert("還原成功");
                            location.reload();
                        } else {
                            alert("還原失敗：" + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Status:', status);
                        console.log('Error:', error);
                        console.log('Response:', xhr.responseText);
                        alert("還原失敗：" + (xhr.responseText ? JSON.parse(xhr.responseText).message : "系統錯誤"));
                    }
                });
            }
        });

        // 永久刪除
        $(".delete-permanent-btn").click(function() {
            let id = $(this).data("id");
            if (confirm("確定要永久刪除這個商品嗎？此操作無法復原！")) {
                $.ajax({
                    url: "product-delete-permanent.php",
                    method: "POST",
                    data: { id: id },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            alert("永久刪除成功");
                            location.reload();
                        } else {
                            alert("刪除失敗：" + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        alert("刪除失敗：系統錯誤");
                    }
                });
            }
        });
    });
    </script>
</body>
</html>