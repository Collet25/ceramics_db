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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kaisei+Decol&display=swap" rel="stylesheet">
    <title>查看優惠券</title>
    <style>

        .detail-table {
            
            width: 100%;
            margin: auto;
            /* max-width: 1000px; */
            border-collapse: collapse;
            font-size: 18px;
            border: 1px dashed rgb(97, 85, 85);
            background: #fff;
            padding-bottom: 10px;
        }

        .detail-table th,
        .detail-table td {
            padding: 8px;
        }

        .detail-table th {
            font-weight: bold;
            color: #333;
            white-space: nowrap;
            vertical-align: middle;
            display: table-cell;
            padding: 18px;

        }

        .detail-table td {
            white-space: nowrap;
            /* 防止文字換行 */
            color: #555;
        }

        .detail-table td[colspan="4"] {
            font-weight: bold;
            color: #222;
        }

        /* 遮罩背景 */
        .modal-overlay {
            display: none;
            /* 預設隱藏 */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        /* 優惠券彈窗 */
        .modal-coupon {
            background: linear-gradient(70deg, #ff9a9e 0%, rgb(246, 200, 188) 50%, #fad07a 100%);
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            position: relative;
            font-family: "Kaisei Decol", serif;
        }

        /* 關閉按鈕 */
        .close-modal {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 20px;
            cursor: pointer;
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
        <div class="d-flex justify-content-start align-items-center ms-6">
            <a href="coupon.php" class="btn btn-outline-secondary">
                <i class="bi bi-chevron-double-left fa-sm"></i>
            </a>
            <h5 class="mb-0 ms-2">優惠券查看</h5>
        </div>
        <hr>


        <!-- 自訂彈窗 -->
        <div class="modal-overlay" id="couponOverlay">
            <div class="modal-coupon">
                <span class="close-modal" id="closeModal">&times;</span>
                <h3 class="text-dark"><?= $row["name"] ?></h3>
                <h6>折扣碼：<?= $row["code"] ?></h6>
                <h5><?= $row["categories"] ?>適用</h5>
                <p id="discount" class="discount"><?= $row["discount"] ?></p>
                <p>(全館滿$<?= $row["minSpend"] ?>元可折)</p>
                <p class="expiry">有效期限：<?= $row["endDate"] ?></p>
            </div>
        </div>

        <div class="col-lg-12 col-12 d-flex justify-content-center">
            <?php if ($userCount > 0): ?>
                <div class="table-responsive p-5 card" style="width: 700px">
                    <table class="detail-table py-4">
                        <thead>
                            <tr>
                                <div class="d-flex justify-content-center mb-3">
                                    <h4 class="text-center">詳細資訊 </h4>
                                    <a class="text-decoration-none text-dark ps-2"
                                        href="coupon-edit.php?id=<?= $row["id"] ?>" title="編輯" aria-label="編輯">
                                        <i class="fa-solid fa-pen-to-square fa-fw"></i>
                                    </a>
                                </div>
                            </tr>
                        </thead>
                        <tbody class="pb-5">
                            <tr>
                                <th>ID</th>
                                <td><?= ($row["id"]) ?></td>
                                <th>名稱</th>
                                <td><?= ($row["name"]) ?></td>
                            </tr>
                            <tr>
                                <th>折扣碼</th>
                                <td><?= ($row["code"]) ?></td>
                                <th>適用範圍</th>
                                <td><?= ($row["categories"]) ?></td>
                            </tr>
                            <tr>
                                <th>類型</th>
                                <td id="discountType"><?= ($row["discountType"]) ?></td>
                                <th>折扣</th>
                                <td id="discount"><?= ($row["discount"]) ?></td>
                            </tr>
                            <tr>
                                <th>低消金額</th>
                                <td>$<?= ($row["minSpend"]) ?></td>
                                <th>發行數量</th>
                                <td><?= number_format($row["quantity"]) ?> 張</td>
                            </tr>
                            <tr>
                                <th>有效日期</th>
                                <td colspan="3"><?= htmlspecialchars($row["startDate"]) ?> ~ <?= htmlspecialchars($row["endDate"]) ?></td>
                            </tr>
                            <tr>
                                <th>建立時間</th>
                                <td><?= htmlspecialchars($row["created_at"]) ?></td>
                                <th>狀態</th>
                                <td><?php if ($row['status'] == '啟用'): ?>
                                        <strong class="text-light bg-warning">啟用</span>
                                        <?php else: ?>
                                            <strong class="text-white bg-danger">停用</strong>
                                        <?php endif; ?>
                                </td>
                            </tr>
                            <!-- <tr>
                                <th>檢視優惠券</th>
                                <td>
                                    <button class="btn btn-primary" id="openModal">預覽</button>
                                </td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>
        </div>
        </div>

    <?php else: ?>
        <h2 class="text-center text-danger">⚠ 使用者不存在</h2>
    <?php endif; ?>
    </div>




    <?php include("../footer.php") ?>
    </main>




    <!-- 動態替換$ & % -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            console.log("DOM 已加載，開始執行 JS");

            let typeElement = document.getElementById("discountType");
            let discountElements = document.querySelectorAll("#discount"); // 選取所有 discount

            console.log("找到類型:", typeElement ? typeElement.innerText.trim() : "無");
            console.log("找到折扣數量:", discountElements.length);

            if (typeElement && discountElements.length > 0) {
                let discountType = typeElement.innerText.trim();

                discountElements.forEach(discountElement => {
                    let discountValue = parseFloat(discountElement.innerText.trim());

                    if (!isNaN(discountValue)) {
                        discountElement.innerText = (discountType === "現金") ? `$${discountValue}` : `${discountValue}%`;
                        console.log("更新後的折扣:", discountElement.innerText);
                    }
                });
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let openModal = document.getElementById("openModal");
            let closeModal = document.getElementById("closeModal");
            let modalOverlay = document.getElementById("couponOverlay");

            // 點擊按鈕顯示彈窗
            openModal.addEventListener("click", function() {
                modalOverlay.style.display = "flex";
            });

            // 點擊關閉按鈕隱藏彈窗
            closeModal.addEventListener("click", function() {
                modalOverlay.style.display = "none";
            });

            // 點擊彈窗外部時關閉
            modalOverlay.addEventListener("click", function(event) {
                if (event.target === modalOverlay) {
                    modalOverlay.style.display = "none";
                }
            });
        });
    </script>

    <?php include("../js.php") ?>
</body>

</html>