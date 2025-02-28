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
    <title>查看優惠券</title>
    <style>
        /* *{
            border: solid 1px red;
        } */
        .coupon-card {
            display: flex;
            flex-direction: column;
            max-width: 600px;
            background:rgb(252, 246, 244);
            /* border-radius: 12px; */
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 2px dashed #7B2D12;
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

        .coupon-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            padding: 16px;
        }

        .coupon-section {
            width: 48%;
            /* 讓資訊橫向排列 */
            margin-bottom: 8px;
            display: flex;
            flex-direction: column;
        }

        .label {
            font-weight: bold;
            color: #333;
            font-size: 20px;
        }

        .value {
            font-size: 22px;
            color: #EA580C;
        }

        .coupon-footer {
            display: flex;
            justify-content: space-between;
            padding: 12px;
        }


        .btn-outline-brown {
            color: #7B2D12;
            border: 1px dotted  #7B2D12;
            background: transparent;
            border-radius: 3px;
            padding: 8px 16px;

        }

        .btn-outline-brown:hover {
            background: #7B2D12;
            color: white;
            border: 1px solid #7B2D12;

        }

        .btn-outline-brown:active {
            background: #5E1F0D;
            border-color: #5E1F0D;
            box-shadow: none;
            transform: translateY(1px);
        }

        .text-brown{
            color: #7B2D12;
        }
        .text-brown:hover{
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

<body class="g-sidenav-show" style="overflow-y: hidden;">
    <!-- aside -->
    <?php include("../aside.php") ?>

    <!-- Main content -->
    <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
        <!-- Navbar -->
        <?php include("../navbar.php") ?>
        <!-- <div class="ms-3 col-4 ">
            <a href="coupon.php" class="btn btn-cancel" title="返回" aria-label="返回">
                <i class="fa-solid fa-circle-left"></i> 返回
            </a> -->
        </div>
        <div class="container">
            <div class="row align-items-center flex-column">

                <div class="col-11 col-sm-11 col-md-11 col-lg-10">
                    <div class=" fs-6">
                        <div class="row justify-content-center">
                            <?php if ($userCount > 0): ?>
                                <div class="coupon-card m-1">
                                    <div class="coupon-header position-relative text-center">

                                        <!-- 右上角編輯按鈕 -->
                                        <a class="text-decoration-none text-brown position-absolute top-0 end-0 "
                                            href="coupon-edit.php?id=<?= $row["id"] ?>" title="編輯" aria-label="編輯">
                                            <i class="fa-solid fa-pen-to-square fa-lg"></i>
                                        </a>

                                        <!-- 標題與折扣碼置中 -->
                                        <h2 class="mb-1"><?= $row["name"] ?></h2>
                                        <p class="mb-0 fs-5 ">折扣碼：<strong><?= $row["code"] ?></strong></p>
                                    </div>

                                    <div class="coupon-content d-flex justify-content-center">
                                        <div class="coupon-section">
                                            <span class="label">類型：</span>
                                            <span class="value"><?= $row["discountType"] ?></span>
                                        </div>
                                        <div class="coupon-section">
                                            <span class="label">折扣：</span>
                                            <span class="value"><?= $row["discount"] ?></span>
                                        </div>
                                        <div class="coupon-section">
                                            <span class="label">低消金額：</span>
                                            <span class="value">$<?= number_format($row["minSpend"]) ?></span>
                                        </div>
                                        <div class="coupon-section">
                                            <span class="label">發行數量：</span>
                                            <span class="value"><?= number_format($row["quantity"]) ?> 張</span>
                                        </div>
                                        <div class="coupon-section">
                                            <span class="label">適用範圍：</span>
                                            <span class="value"><?= $row["categories"] ?></span>
                                        </div>
                                        <div class="coupon-section">
                                            <span class="label">有效日期：</span>
                                            <span class="value"><?= $row["startDate"] ?> - <?= $row["endDate"] ?></span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-start">
                                        <a href="coupon.php" class="text-decoration-none btn-outline-brown p-2">
                                            <div class=" fs-6 mb-0"> <i class="fa-solid fa-circle-left"></i> 返回
                                            </div>
                                        </a>

                                    </div>
                                </div>
                        </div>

                    <?php else: ?>
                        <h2>⚠ 使用者不存在</h2>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>


        <?php include("../footer.php") ?>

    </main>




    <!-- 動態替換$ & % -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".coupon-content").forEach(function(coupon) {
                let typeElement = coupon.querySelector(".coupon-section .value"); // 類型
                let discountElement = typeElement?.parentElement?.nextElementSibling?.querySelector(".value"); // 折扣

                if (typeElement && discountElement) {
                    let discountType = typeElement.innerText.trim();
                    let discountValue = parseFloat(discountElement.innerText.trim());

                    if (!isNaN(discountValue)) {
                        discountElement.innerText = (discountType === "現金") ? `$${discountValue}` : `${discountValue}%`;
                    }
                }
            });
        });
    </script>

    <?php include("../js.php") ?>
</body>

</html>