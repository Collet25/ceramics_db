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
        body {
            cursor: url("https://abs.twimg.com/a/1446542199/img/t1/web_heart_animation.png") 16 16, auto;
        }

        .product-image-container {
            position: relative;
            width: 100%;
            height: 100%;
            padding: 20px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, #ffffff, #f5f5f5);
            box-shadow: 0 10px 30px rgba(154, 52, 18, 0.08);
            border: 1px solid rgba(154, 52, 18, 0.05);
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(154, 52, 18, 0.05);
            transition: all 0.3s ease;
        }

        .product-image-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(154, 52, 18, 0.1);
        }

        .product-image {
            width: 100%;
            max-width: 500px;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(154, 52, 18, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
            object-fit: contain;
        }

        .product-image:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(154, 52, 18, 0.15);
        }

        .modal-dialog {
            max-width: 600px;
        }

        .modal-body {
            height: 600px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #fff;
        }

        .modal-image {
            width: 500px;
            height: 500px;
            object-fit: contain;
        }

        .info-label {
            font-weight: 600;
            color: #9A3412;
            font-size: 1rem;
            letter-spacing: 0.5px;
            position: relative;
            display: flex;
            align-items: center;
            gap: 6px;
            padding-bottom: 2px;
            width: 100%;
            margin-bottom: 10px;
        }

        .info-label::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50%;
            height: 1px;
            background: linear-gradient(90deg, #9A3412, rgba(154, 52, 18, 0.1));
            transform: scaleX(1);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .info-label i {
            font-size: 1.2rem;
            color: #EA580C;
            background: linear-gradient(135deg, #9A3412, #EA580C);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 1px 2px rgba(154, 52, 18, 0.1));
        }

        .row:hover .info-label::after {
            background: linear-gradient(90deg, #EA580C, rgba(234, 88, 12, 0.1));
        }

        .product-info {
            background: linear-gradient(165deg, rgba(255, 245, 231, 0.95), rgba(245, 227, 195, 0.95));
            padding: 25px;
            border-radius: 25px;
            position: relative;
            background-image: 
                linear-gradient(rgba(154, 52, 18, 0.05) 1px, transparent 1px),
                linear-gradient(165deg, rgba(255, 245, 231, 0.95), rgba(245, 227, 195, 0.95));
            background-size: 100% 2rem;
            line-height: 2rem;
        }

        .product-info::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at top right, rgba(234, 88, 12, 0.1), transparent 70%);
            pointer-events: none;
        }

        .product-info .row {
            margin-bottom: 0;
            padding: 0.3rem 1rem;
            border-radius: 8px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            background: transparent;
            min-height: 2rem;
            display: flex;
            align-items: center;
        }

        .product-info .row::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .product-info .row:hover::before {
            opacity: 1;
        }

        .btn-primary, .btn-outline-secondary {
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .btn-primary::before, .btn-outline-secondary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 50%);
            transform: translate(-50%, -50%);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .btn-primary:hover::before, .btn-outline-secondary:hover::before {
            opacity: 1;
            animation: ripple 1s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes ripple {
            from {
                transform: translate(-50%, -50%) scale(0);
                opacity: 1;
            }
            to {
                transform: translate(-50%, -50%) scale(1);
                opacity: 0;
            }
        }

        .modal-content {
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            border: none;
        }

        .modal-header {
            background: linear-gradient(135deg, #9A3412, #EA580C);
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .modal-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at top right, rgba(255,255,255,0.2), transparent 70%);
            pointer-events: none;
        }

        .modal-title {
            font-weight: 700;
            letter-spacing: 1px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .description-box {
            background: rgba(255, 255, 255, 0.9);
            padding: 1.2rem;
            border-radius: 15px;
            box-shadow: 
                0 8px 20px rgba(154, 52, 18, 0.08),
                inset 0 2px 4px rgba(255, 255, 255, 0.5);
            position: relative;
            line-height: 1.4;
            background-image: 
                linear-gradient(rgba(154, 52, 18, 0.05) 1px, transparent 1px);
            background-size: 100% 1.4rem;
            min-height: 7rem;
        }

        .description-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.2), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .description-box:hover::before {
            opacity: 1;
        }

        .page-header {
            position: relative;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
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

        .breadcrumb {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .breadcrumb a {
            color: #6c757d;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .breadcrumb a:hover {
            color: #9A3412;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 600;
            color: #9A3412;
            margin-bottom: 0.5rem;
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

        .btn-primary {
            background: linear-gradient(135deg, #9A3412, #EA580C);
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(154, 52, 18, 0.3);
            background: linear-gradient(135deg, #EA580C, #9A3412);
        }

        .product-info .row {
            margin-bottom: 1rem;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .product-info .row:hover {
            background: rgba(255, 255, 255, 0.5);
            transform: translateX(5px);
        }

        .description-box {
            background: #fff;
            padding: 1rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(154, 52, 18, 0.05);
            transition: all 0.3s ease;
        }

        .description-box:hover {
            box-shadow: 0 4px 15px rgba(154, 52, 18, 0.1);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* 響應式優化 */
        @media (max-width: 768px) {
            .product-info {
                margin-top: 1.5rem;
                padding: 1.2rem;
            }
            
            .info-label {
                font-size: 0.9rem;
                letter-spacing: 0.5px;
            }
            
            .product-info .row {
                padding: 0.8rem;
                margin-bottom: 1rem;
            }
            
            .description-box {
                padding: 1rem;
            }
            
            .product-image {
                max-width: 100%;
            }
            
            .page-title {
                font-size: 1.5rem;
            }
        }

        .product-info .col-8 {
            display: flex;
            align-items: center;
            height: 2rem;
        }

        .description-container {
            margin-top: 0.5rem;
        }

        .mb-3 {
            margin-bottom: 0.8rem !important;
        }

        .mb-4 {
            margin-bottom: 1rem !important;
        }
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
                        <div class="container ">
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
                                        <!-- <h2 class="mb-4"><?= $product["name"] ?></h2> -->

                                        <div class="mb-3">
                                            <div class="row">
                                                <div class="col-4">
                                                    <span class="info-label">
                                                        <i class="bi bi-tag-fill"></i>
                                                        商品分類：
                                                    </span>
                                                </div>
                                                <div class="col-8">
                                                    <?= $product["category_name"] ?> / <?= $product["subcategory_name"] ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="row">
                                                <div class="col-4">
                                                    <span class="info-label">
                                                        <i class="bi bi-currency-dollar"></i>
                                                        商品價格：
                                                    </span>
                                                </div>
                                                <div class="col-8">
                                                    NT$ <?= number_format($product["price"]) ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="row">
                                                <div class="col-4">
                                                    <span class="info-label">
                                                        <i class="bi bi-box-seam-fill"></i>
                                                        材質：
                                                    </span>
                                                </div>
                                                <div class="col-8">
                                                    <?= $product["material_name"] ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="row">
                                                <div class="col-4">
                                                    <span class="info-label">
                                                        <i class="bi bi-geo-alt-fill"></i>
                                                        產地：
                                                    </span>
                                                </div>
                                                <div class="col-8">
                                                    <?= $product["origin_name"] ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="row">
                                                <div class="col-4">
                                                    <span class="info-label">
                                                        <i class="bi bi-clock-fill"></i>
                                                        更新時間：
                                                    </span>
                                                </div>
                                                <div class="col-8">
                                                    <?= date("Y/m/d H:i", strtotime($product["updated_at"])) ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <div class="info-label mb-2 ms-2">
                                                <i class="bi bi-card-text"></i>
                                                商品描述：
                                            </div>
                                            <div class="p-3 bg-white rounded">
                                                <?= nl2br($product["description"]) ?>
                                            </div>
                                        </div>

                                        <div class="d-flex ">
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