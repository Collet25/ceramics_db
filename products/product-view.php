<?php
require_once("../ceramics_db_connect.php");

// 檢查是否有傳入商品ID
if (!isset($_GET["id"])) {
    die("請指定商品ID");
}

$id = $_GET["id"];

// 獲取商品詳細資料
$sql = "SELECT p.*, 
        c.name AS category_name,
        s.name AS subcategory_name,
        m.name AS material_name,
        o.name AS origin_name
        FROM products p
        LEFT JOIN categories c ON p.category = c.name
        LEFT JOIN subcategories s ON p.subcategory = s.name
        LEFT JOIN materials m ON p.material = m.name
        LEFT JOIN origins o ON p.origin = o.name
        WHERE p.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("找不到該商品");
}

$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../logo-img/head-icon.png">
    <title>
        商品檢視
    </title>
    <?php include("../css.php"); ?>
    <style>
        .product-image-container {
            position: relative;
            width: 100%;
            height: 100%;
            padding: 20px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-image {
            width: 100%;
            max-width: 500px;
            /* 設定最大寬度 */
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.3s ease;
            object-fit: contain;
            /* 保持圖片比例 */
        }

        .product-image:hover {
            transform: scale(1.02);
        }

        /* Modal 樣式調整 */
        .modal-dialog {
            max-width: 600px;
            /* 調整 Modal 視窗大小 */
        }

        .modal-body {
            height: 600px;
            /* 固定高度 */
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }

        .modal-image {
            width: 500px;
            /* 固定寬度 */
            height: 500px;
            /* 固定高度 */
            object-fit: contain;
            /* 保持圖片比例 */
        }

        .info-label {
            font-weight: bold;
            color: #666;
        }

        .product-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            height: 100%;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .breadcrumb {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .breadcrumb a {
            color: #6c757d;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            color: #0d6efd;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: bold;
            margin: 0;
        }

        /* .products-view {
            margin-top: -1rem;
        } */

    </style>

</head>

<body class="g-sidenav-show">

    <?php include("../aside.php"); ?>


    <!--  -->
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">


        <!-- Navbar -->
        <?php include("../navbar.php"); ?>





        <!-- **********商品列表********* -->
        <div class="container-fluid py-4 products-view">
            <div class="row">
                <div class="col-12">
                    <div class="mb-4">

                        <!-- ******商品列表********* -->
                        <div class="container py-4">
                            <div class="page-header">
                                <div class="breadcrumb">
                                    <!-- <a href="../index.php">首頁</a> /
                                    <a href="product-list.php">商品管理</a> -->
                                </div>
                                
                                <div class="mt-3">
                                    <a href="product-list.php" class="btn btn-outline-secondary">
                                        <i class="bi bi-chevron-double-left"></i>
                                    </a>
                                </div>
                                <div>
                                <h1 class="page-title ms-3"><?= $product["name"] ?></h1>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="product-image-container">
                                        <img src="../uploads/<?= $product["image"] ?>"
                                            alt="<?= $product["name"] ?>"
                                            class="product-image"
                                            onclick="showImageModal(this.src)">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="product-info">
                                        <h2 class="mb-4"><?= $product["name"] ?></h2>

                                        <div class="mb-3">
                                            <div class="row">
                                                <div class="col-4">
                                                    <span class="info-label">商品分類：</span>
                                                </div>
                                                <div class="col-8">
                                                    <?= $product["category_name"] ?> / <?= $product["subcategory_name"] ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="row">
                                                <div class="col-4">
                                                    <span class="info-label">商品價格：</span>
                                                </div>
                                                <div class="col-8">
                                                    NT$ <?= number_format($product["price"]) ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="row">
                                                <div class="col-4">
                                                    <span class="info-label">材質：</span>
                                                </div>
                                                <div class="col-8">
                                                    <?= $product["material_name"] ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="row">
                                                <div class="col-4">
                                                    <span class="info-label">產地：</span>
                                                </div>
                                                <div class="col-8">
                                                    <?= $product["origin_name"] ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="row">
                                                <div class="col-4">
                                                    <span class="info-label">更新時間：</span>
                                                </div>
                                                <div class="col-8">
                                                    <?= date("Y/m/d H:i", strtotime($product["updated_at"])) ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <div class="info-label mb-2">商品描述：</div>
                                            <div class="p-3 bg-white rounded">
                                                <?= nl2br($product["description"]) ?>
                                            </div>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <a href="product-edit.php?id=<?= $product["id"] ?>" class="btn btn-primary">
                                                <i class="bi bi-pencil"></i> 編輯
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 圖片放大檢視的 Modal -->
                        <div class="modal fade" id="imageModal" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"><?= $product["name"] ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center p-0">
                                        <img src="" id="modalImage" class="modal-image">
                                    </div>
                                </div>
                            </div>
                        </div>





                    </div>
                </div>
            </div>
        </div>



        <!-- 頁尾 -->
        <?php include("../footer.php"); ?>

    </main>

    <!-- 側邊欄 -->
    <?php include("../aside-fixed.php"); ?>


    <?php include("../js.php"); ?>



    <!--  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // 圖片放大檢視功能
        function showImageModal(src) {
            document.getElementById('modalImage').src = src;
            new bootstrap.Modal(document.getElementById('imageModal')).show();
        }
    </script>

</body>

</html>