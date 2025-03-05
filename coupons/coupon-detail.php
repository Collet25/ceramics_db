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
        .leftSide {
            font-family: "Kaisei Decol", serif;
            background-image: linear-gradient( 101.3deg,  rgba(238,145,115,1) 11.3%, rgba(249,191,161,1) 89.2% );
            /* background-image: linear-gradient(90deg, rgba(245, 212, 212, 1) 8.5%, rgba(252, 251, 224, 1) 80%); */
            /* background-image: linear-gradient( 174.2deg,  rgba(255,244,228,1) 7.1%, rgba(240,246,238,1) 67.4% ); */
            color: #222;
            border-radius: 20px;
            border-right: 10px dashed #fff;
        }

        .detail-table {
            background: #fff;
            font-family: "Kaisei Decol", serif;
            width: 700px;
            /* margin: auto; */
            /* max-width: 1000px; */
            border-collapse: collapse;
            font-size: 18px;
            /* border: 1px dashed rgb(97, 85, 85); */

            padding-bottom: 10px;
            border-radius: 10px;
            /* border: 1px solid saddlebrown; */


        }

        .detail-table th,
        .detail-table td {
            padding: 10px 10px 10px 18px;
            /* border-bottom: 1px solid #555; */
        }

        .detail-table th {
            font-weight: bold;
            color: #333;
            white-space: nowrap;
            vertical-align: middle;
            display: table-cell;
            padding: 18px;
            padding-left: 40px;
        }

        .detail-table td {
            white-space: nowrap;
            /* 防止文字換行 */
            color: black;
            text-align: start;
            padding-right: 20px;
        }


        .detail-table td[colspan="4"] {
            font-weight: bold;
            color: #222;
        }

        .all-set:hover {
            transform: translateY(-5px);
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
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
        <div class="d-flex justify-content-start align-items-center ms-6 ">
            <a href="coupon.php" class="btn btn-outline-secondary bg-light">
                <i class="bi bi-chevron-double-left fa-sm"></i>
            </a>
            <h5 class="mb-0 ms-2">優惠券查看</h5>
        </div>
        <hr>
        <div class="col-lg-12 col-12 d-flex justify-content-center ">
            <?php if ($userCount > 0): ?>

                <div class="p-5 d-flex flex-column justify-content-between leftSide shadow" style="width: 300px;">
                    <div class="text-center">
                        <h3>優惠券 #<?= ($row["id"]) ?></h3>
                    </div>
                    <div class="text-center">
                        <a class="text-decoration-none text-dark px-2"
                            href="coupon-edit.php?id=<?= $row["id"] ?>" title="編輯" aria-label="編輯">
                            <i class="fa-solid fa-pen-to-square fa-fw"></i>
                        </a>
                    </div>
                </div>
                <table class="detail-table py-4 shadow all-set">
                    <tbody class="pb-5">
                        <tr>
                            <th>名稱</th>
                            <td>
                                <div class="d-inline-block p-2"><?= ($row["name"]) ?></div>
                            </td>

                        </tr>
                        <tr>
                            <th>折扣碼</th>
                            <td><?= ($row["code"]) ?></td>
                        </tr>
                        <tr>
                            <th>適用範圍</th>
                            <td><?= ($row["categories"]) ?></td>
                        </tr>
                        <tr>
                            <th>類型</th>
                            <td id="discountType"><?= ($row["discountType"]) ?></td>
                        </tr>
                        <tr>
                            <th>折扣</th>
                            <td id="discount"><?= ($row["discount"]) ?></td>
                        </tr>
                        <tr>
                            <th>低消金額</th>
                            <td>$<?= ($row["minSpend"]) ?></td>
                        </tr>
                        <tr>
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

                        </tr>
                        <tr>
                            <th>狀態</th>
                            <td><?php if ($row['status'] == '啟用'): ?>
                                    <strong class="d-inline-block p-1 text-light bg-warning">啟用</strong>
                                <?php else: ?>
                                    <strong class="d-inline-block p-1 text-white bg-danger">停用</strong>
                                <?php endif; ?>
                            </td>
                        </tr>
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