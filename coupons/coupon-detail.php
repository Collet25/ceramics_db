<?php
if (!isset($_GET["id"])) {
    header("location: coupon.php");
}
// if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
//     die("錯誤：無效的 ID");
// }
// $id = intval($_GET["id"]); // 確保 ID 是數字
// 防呆機制，若無id則跳回頁面
// var_dump($id);
// exit;

$id = $_GET["id"];

require_once("../ceramics_db_connect.php");
$sql = "SELECT * FROM coupons WHERE id=$id AND valid=1";
$result = $conn->query($sql);

$row = $result->fetch_assoc();
$userCount = $result->num_rows;
// var_dump($row);

?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../logo-img/head-icon.png">
    <title>查看優惠券</title>
    <style>
        /* *{
            border: solid 1px red;
        } */
        .coupon-card {
            display: flex;
            flex-direction: column;
            max-width: 600px;
            /* background: #f8f9fa; */
            /* border-radius: 12px; */
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 2px solid #7B2D12;
            /* 點線邊框 */
            padding: 16px;
            margin: 20px auto;

        }

        .coupon-header {
            text-align: center;
            color: #7B2D12;
            padding: 12px;
            font-size: 1.2em;
            font-weight: bold;
            border-bottom: #7B2D12 2px solid;
        }

        /* .coupon-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            padding: 16px;
        } */

        .coupon-section {
            width: 46%;
            /* 讓資訊橫向排列 */
            margin-bottom: 8px;
            display: flex;
            flex-direction: column;
        }

        .label {
            font-weight: bold;
            color: #333;
            font-size: 16px;
        }

        .value {
            font-size: 19px;
            color: #EA580C;
        }

        .coupon-footer {
            display: flex;
            justify-content: space-between;
            padding: 12px;
        }


        .text-brown {
            color: #7B2D12;
        }

        .text-brown:hover {
            color: #7B2D12;
        }



        /* RWD: 手機直式改成單欄 */
        @media (max-width: 480px) {
            .coupon-content {
                flex-direction: column;
            }

            .coupon-section {
                width: 100%;
            }

            .btn {
                width: 100%;
                margin-top: 8px;
            }


        }
    </style>
    <?php include("../css.php") ?>
</head>

<body class="g-sidenav-show">
    <!-- aside -->
    <?php include("../aside.php") ?>

    <!-- Main content -->
    <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg grid">
        <!-- Navbar -->
        <?php include("../navbar.php") ?>

        <div class="container-fluid d-flex justify-content-center py-4 px-5">
            <div class="card col-12 col-lg-8">
                <div class="card-header pt-3">
                    <div class="d-flex justify-content-between">
                        <div class=" mb-3">
                            <h6 class="font-weight-bold fs-4 mb-0 ms-2"><i class=" fa-solid fa-tags"></i> 檢視優惠券</h6>
                        </div>
                        <!-- 按鈕區 -->
                        <div class="d-flex justify-content-end gap-2 mb-3">
                            <a href="coupon.php" class="text-decoration-none btn btn-cancel p-2">
                                <div class=" fs-6 mb-0"> <i class="fa-solid fa-circle-left"></i> 返回列表
                                </div>
                            </a>
                            <a class="btn btn-primary text-brown d-flex align-items-center"
                                href="coupon-edit.php?id=<?= $row["id"] ?>" title="編輯" aria-label="編輯">
                                <i class="fa-solid fa-pen-to-square fa-lg me-1"></i> 編輯
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body d-flex justify-content-center">
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <div class="fs-6">
                                <?php if ($userCount > 0): ?>
                                    <div class="coupon-card m-1">
                                        <div class="coupon-header text-center">
                                            <h4 class="mb-1"><?= htmlspecialchars($row["name"]) ?></h4>
                                            <p class="mb-0">折扣碼：<strong><?= htmlspecialchars($row["code"]) ?></strong></p>
                                        </div>

                                        <div class=" d-flex flex-wrap justify-content-center gap-3">
                                            <div class="coupon-section">
                                                <span class="label">ID：</span>
                                                <span class="value"><?= ($row["id"]) ?></span>
                                            </div>
                                            <div class="coupon-section">
                                                <span class="label">適用範圍：</span>
                                                <span class="value"><?= ($row["categories"]) ?></span>
                                            </div>
                                            <div class="coupon-section">
                                                <span class="label">類型：</span>
                                                <span class="value" id="discountType"><?= ($row["discountType"]) ?></span>
                                            </div>
                                            <div class="coupon-section">
                                                <span class="label">折扣：</span>
                                                <span class="value" id="discount"><?= ($row["discount"]) ?></span>
                                            </div>
                                            <div class="coupon-section">
                                                <span class="label">低消金額：</span>
                                                <span class="value">$<?= ($row["minSpend"]) ?></span>
                                            </div>
                                            <div class="coupon-section">
                                                <span class="label">發行數量：</span>
                                                <span class="value"><?= ($row["quantity"]) ?> 張</span>
                                            </div>
                                            <div class="coupon-section">
                                                <span class="label">有效日期：</span>
                                                <span class="value"><?= htmlspecialchars($row["startDate"]) ?> ~ <?= htmlspecialchars($row["endDate"]) ?></span>
                                            </div>
                                            <div class="coupon-section">
                                                <span class="label">建立時間：</span>
                                                <span class="value"><?= htmlspecialchars($row["created_at"]) ?></span>
                                            </div>
                                            <div class="coupon-section">
                                                <span class="label">狀態：</span>
                                                <span class="value"><?= htmlspecialchars($row["status"]) ?></span>
                                            </div>
                                            <!-- 為了排版方便增加同底色的欄位 -->
                                            <div class="coupon-section text-white">
                                                <span class="label text-white">狀態：</span>
                                                <span class="value text-white"><?= htmlspecialchars($row["status"]) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <h2 class="text-center text-danger">⚠ 使用者不存在</h2>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <?php include("../footer.php") ?>

    </main>




    <!-- 動態替換$ & % -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            console.log("DOM 已加載，開始執行 JS");

            let typeElement = document.getElementById("discountType");
            let discountElement = document.getElementById("discount");

            console.log("找到類型:", typeElement ? typeElement.innerText.trim() : "無");
            console.log("找到折扣:", discountElement ? discountElement.innerText.trim() : "無");

            if (typeElement && discountElement) {
                let discountType = typeElement.innerText.trim();
                let discountValue = parseFloat(discountElement.innerText.trim());

                if (!isNaN(discountValue)) {
                    discountElement.innerText = (discountType === "現金") ? `$${discountValue}` : `${discountValue}%`;
                    console.log("更新後的折扣:", discountElement.innerText);
                }
            }
        });
    </script>

    <?php include("../js.php") ?>
</body>

</html>