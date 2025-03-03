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
    <link rel="icon" type="image/png" href="../logo-img/head-icon.png">
    <title>已刪除商品</title>
    <?php include("../css.php"); ?>
    <link rel="stylesheet" href="../products/style_p.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        :root {
            --bg-header: linear-gradient(90deg, #9A3412, #EA580C);
            --text-light: #fff;
        }

        body {
            cursor: url("https://abs.twimg.com/a/1446542199/img/t1/web_heart_animation.png") 16 16, auto;
        }

        .page-header {
            position: relative;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .page-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, #9A3412, #EA580C);
            border-radius: 2px;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 600;
            color: #9A3412;
            margin-bottom: 0.5rem;
        }

        /* 表格容器樣式 */
      

        .table-responsive::before {
            display: none;
        }

        /* 表格樣式 */
        .table {
            margin-bottom: 0;
        }

        .table thead {
            position: relative;
            background: linear-gradient(90deg, #9A3412, #EA580C);
        }

        .table thead::after {
            display: none;
        }

        .table th {
            background: none;
            color: #fff;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 1rem;
            border: none;
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
            letter-spacing: 0.5px;
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid rgba(154, 52, 18, 0.1);
            background: none;
            text-align: center;
        }

        .table tr:last-child td {
            border-bottom: none;
        }

        .table tr:hover td {
            background-color: rgba(255, 255, 255, 0.5);
        }

        .table td.text-start,
        .table th.text-start {
            text-align: left;
        }

        /* 商品圖片樣式 */
        .product-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(154, 52, 18, 0.1);
            transition: all 0.3s ease;
        }

        .product-img:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(154, 52, 18, 0.15);
        }

        /* 按鈕樣式 */
        .btn-icon {
            width: 35px;
            height: 35px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            margin: 0 3px;
            transition: all 0.3s ease;
            border-width: 2px;
        }

        .btn-icon i {
            font-size: 1.1rem;
        }

        .btn-icon:hover {
            transform: translateY(-2px);
        }

        .btn-outline-success {
            border-color: #9A3412;
            color: #9A3412;
        }

        .btn-outline-success:hover {
            background: linear-gradient(135deg, #9A3412, #EA580C);
            border-color: transparent;
            color: white;
            box-shadow: 0 4px 15px rgba(154, 52, 18, 0.2);
        }

        .btn-outline-danger {
            border-color: #dc3545;
            color: #dc3545;
        }

        .btn-outline-danger:hover {
            background: #dc3545;
            border-color: #dc3545;
            color: white;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.2);
        }

        .btn-outline-secondary {
            border: 2px solid #9A3412;
            color: #9A3412;
            padding: 0.6rem 1.2rem;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background: #9A3412;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(154, 52, 18, 0.2);
        }

        /* 分頁樣式 */
        .pagination {
            margin-top: 2rem;
        }

        .page-link {
            color: #9A3412;
            border: 2px solid rgba(154, 52, 18, 0.2);
            margin: 0 3px;
            border-radius: 8px;
            padding: 0.5rem 0.8rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background: linear-gradient(135deg, #9A3412, #EA580C);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(154, 52, 18, 0.2);
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, #9A3412, #EA580C);
            border-color: transparent;
            color: white;
            box-shadow: 0 4px 15px rgba(154, 52, 18, 0.2);
        }

        /* 文字樣式 */
        .product-name {
            font-weight: 500;
            color: #9A3412;
        }

        .product-price {
            font-weight: 600;
            color: #EA580C;
        }

        .text-end {
            color: #9A3412;
            font-weight: 500;
            font-size: 0.95rem;
            margin-bottom: 1rem;
        }

        /* 操作按鈕容器 */
        .action-buttons {
            white-space: nowrap;
            text-align: center;
        }

        /* 響應式設計 */
        @media (max-width: 768px) {
            .table-responsive {
                padding: 1rem;
                border-radius: 15px;
            }
            
            .product-img {
                width: 60px;
                height: 60px;
            }
            
            .btn-icon {
                width: 30px;
                height: 30px;
            }
            
            .page-title {
                font-size: 1.5rem;
            }

            .table th, .table td {
                padding: 0.8rem;
            }
        }

        /* SweetAlert2 樣式 */
        .swal2-popup {
            border-radius: 15px;
        }

        .swal2-title {
            color: #9A3412 !important;
        }

        .swal2-confirm {
            background: linear-gradient(135deg, #9A3412, #EA580C) !important;
        }

        .swal2-cancel {
            background: #dc3545 !important;
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
                            <div class="page-header ">
                                <div>
                                    <a href="product-list.php" class="btn btn-outline-secondary">
                                        <i class="bi bi-chevron-double-left mt-5"></i> 
                                    </a>
                                </div>
                                <h1 class="page-title ms-2 fs-2 pb-2">已刪除商品</h1>
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
            Swal.fire({
                title: '確定要還原嗎？',
                text: '商品將會回到商品列表',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#9A3412',
                cancelButtonColor: '#d33',
                confirmButtonText: '還原',
                cancelButtonText: '取消'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "product-restore.php",
                        method: "POST",
                        data: { id: id },
                        dataType: "json",
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: '還原成功！',
                                    text: '商品已回到商品列表',
                                    icon: 'success',
                                    confirmButtonColor: '#9A3412'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: '還原失敗',
                                    text: response.message,
                                    icon: 'error',
                                    confirmButtonColor: '#9A3412'
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: '系統錯誤',
                                text: '還原過程發生錯誤',
                                icon: 'error',
                                confirmButtonColor: '#9A3412'
                            });
                        }
                    });
                }
            });
        });

        // 永久刪除
        $(".delete-permanent-btn").click(function() {
            let id = $(this).data("id");
            Swal.fire({
                title: '確定要永久刪除嗎？',
                text: '此操作無法復原！',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#9A3412',
                cancelButtonColor: '#d33',
                confirmButtonText: '刪除',
                cancelButtonText: '取消'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "product-delete-permanent.php",
                        method: "POST",
                        data: { id: id },
                        dataType: "json",
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: '刪除成功！',
                                    text: '商品已永久刪除',
                                    icon: 'success',
                                    confirmButtonColor: '#9A3412'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: '刪除失敗',
                                    text: response.message,
                                    icon: 'error',
                                    confirmButtonColor: '#9A3412'
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: '系統錯誤',
                                text: '刪除過程發生錯誤',
                                icon: 'error',
                                confirmButtonColor: '#9A3412'
                            });
                        }
                    });
                }
            });
        });
    });
    </script>
</body>
</html>