<?php
if (!isset($_GET["id"])) {
    header("location: coupon.php");
    exit();
}
$now = date("Y-m-d H:i:s");
$id = $_GET["id"];

require_once("../ceramics_db_connect.php");

$sql = "SELECT * FROM coupons WHERE id=$id AND valid=1";
$result = $conn->query($sql);

$userCount = $result->num_rows;
$row = $result->fetch_assoc();

$conn->close();

?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>編輯優惠券</title>
    <style>
        .btn-outline-brown {
            border: 2px solid #9A3412;
            background: transparent;
            border-radius: 10px;
            padding: 0 10px;
        }

        .btn-outline-brown:hover {
            background: #9A3412;
            color: white;
        }

        .coupon-card {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            /* max-width: 1000px; */
            background: rgb(255, 255, 255);
            /* border-radius: 12px; */
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            /* border: 2px dashed #7B2D12; */
            padding: 16px;
            margin: 20px auto;

        }
    </style>
    <?php include("../css.php") ?>
</head>

<body class="g-sidenav-show">
    <!-- aside -->
    <?php include("../aside.php") ?>
    <!-- Modal -->
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="">系統資訊</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    確認要刪除此優惠券?
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-danger" href="couponDelete.php?id=<?= $row["id"] ?>">確認</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <main class=" main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
        <!-- Navbar -->
        <?php include("../navbar.php") ?>
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- 優惠券標題欄 -->
                        <div class="card-header border-bottom pb-0 ">
                            <div class="row align-items-center g-2 pt-2">
                                <div class="d-flex justify-content-between">
                                    <div class=" mb-3">
                                        <h6 class="font-weight-bold fs-4 mb-0 ms-2"><i class=" fa-solid fa-tags"></i> 編輯優惠券</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body mt-3">
                            <div class="table-responsive">
                                <?php if ($userCount > 0): ?>
                                    <form action="doUpdateCoupon.php" method="post">
                                        <input type="hidden" name="id" value="<?= $row["id"] ?>">
                                        <input type="hidden" name="code" value="<?= $row["code"] ?>">

                                        <table class="table table-borderless table-sm" style="border-radius: 0px;">
                                            <tbody>
                                                <tr>
                                                    <th class="text-center align-middle bg-gray-100">編號</th>
                                                    <td><?= $row["id"] ?></td>
                                                    <th class="text-center align-middle bg-gray-100">名稱</th>
                                                    <td>
                                                        <input type="text" class="form-control" name="name"
                                                            value="<?= $row["name"] ?>" title="名稱長度應少於30字">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-center align-middle bg-gray-100">折扣碼</th>
                                                    <td><?= $row["code"] ?></td>
                                                    <th class="text-center align-middle bg-gray-100">適用範圍</th>
                                                    <td>
                                                        <select id="categories" name="categories" class="form-select" required>
                                                            <option value="禮品" <?= ($row["categories"] == "禮品") ? "selected" : "" ?>>禮品</option>
                                                            <option value="圖書影音" <?= ($row["categories"] == "圖書影音") ? "selected" : "" ?>>圖書影音</option>
                                                            <option value="課程" <?= ($row["categories"] == "課程") ? "selected" : "" ?>>課程</option>
                                                        </select>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <th class="text-center align-middle bg-gray-100">低消金額($)</th>
                                                    <td><input type="number" class="form-control" name="minSpend"
                                                            value="<?= $row["minSpend"] ?>">
                                                    </td>
                                                    <th class="text-center align-middle bg-gray-100">發行數量(張)</th>
                                                    <td><input
                                                            type="number"
                                                            class="form-control"
                                                            name="quantity"
                                                            value="<?= $row["quantity"] ?>">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-center align-middle bg-gray-100">類型</th>
                                                    <td>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input p-1" type="radio" name="discountType" id="percentage" value="百分比"
                                                                <?= ($row["discountType"] == "百分比") ? "checked" : "" ?>>
                                                            <label class="form-check-label" for="percentage">百分比折扣</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input p-1" type="radio" name="discountType" id="fixed" value="現金"
                                                                <?= ($row["discountType"] == "現金") ? "checked" : "" ?>>
                                                            <label class="form-check-label" for="fixed">現金折扣</label>
                                                        </div>
                                                    </td>

                                                    <th class="text-center align-middle bg-gray-100">面額</th>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-text" id="discountPrefix">$</span>
                                                            <input type="number" id="discount" name="discount" class="form-control" step="0.01" min="0" required
                                                                value="<?= htmlspecialchars($row["discount"]) ?>">
                                                            <span class="input-group-text" id="discountSuffix">%</span>
                                                        </div>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <th class="text-center align-middle bg-gray-100">開始日期</th>
                                                    <td><input type="date" id="startDate" name="startDate" class="form-control" required
                                                            value="<?= date('Y-m-d', strtotime($row['startDate'])) ?>"></td>
                                                    <th class="text-center align-middle bg-gray-100">結束日期</th>
                                                    <td><input type="date" id="endDate" name="endDate" class="form-control" required
                                                            value="<?= date('Y-m-d', strtotime($row['endDate'])) ?>"></td>
                                                </tr>
                                                <tr>
                                                    <th class="text-center align-middle bg-gray-100">狀態</th>
                                                    <td>
                                                        <div class="d-flex gap-3">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input p-1" type="radio" name="status" id="enabled" value="啟用" <?= ($row["status"] == "啟用") ? "checked" : "" ?>>
                                                                <label class="form-check-label" for="enabled">啟用</label>
                                                            </div>

                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input p-1" type="radio" name="status" id="disabled" value="停用" <?= ($row["status"] == "停用") ? "checked" : "" ?>>
                                                                <label class="form-check-label" for="disabled">停用</label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-center align-middle bg-gray-100">建立時間</th>
                                                    <td><?= $row["created_at"] ?></td>
                                                    <th class="text-center align-middle bg-gray-100">最後更新時間</th>
                                                    <td><?= $now ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        
                                        <div class="d-flex justify-content-between">
                                            <a href="coupon.php" class=" btn btn-cancel">
                                                <div class=" fs-6 mb-0"> <i class="fa-solid fa-circle-left"></i> 返回
                                                </div>
                                            </a>
                                            <div class="d-flex ">
                                                <button type="submit" class="btn btn-primary me-1">
                                                    <i class="fa-regular fa-floppy-disk fa-fw"></i> 儲存
                                                </button>
                                                <button type="button" class="btn btn-outline-danger text-center" data-bs-toggle="modal" data-bs-target="#infoModal">
                                                    <i class="fa-solid fa-trash-can fa-fw"></i> 刪除
                                                </button>
                                            </div>
                                        </div>

                                    </form>
                            </div>
                        <?php else: ?>
                            <h2>使用者不存在</h2>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include("../footer.php") ?>
    </main>

    <!-- 更新輸入框的前綴（$ 或 %） -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let discountTypeRadios = document.querySelectorAll("input[name='discountType']");
            let discountInput = document.getElementById("discount");
            let discountPrefix = document.getElementById("discountPrefix");
            let discountSuffix = document.getElementById("discountSuffix");

            function updateDiscountSymbol() {
                let selectedType = document.querySelector("input[name='discountType']:checked").value;
                if (selectedType === "現金") {
                    discountPrefix.innerText = "$";
                    discountSuffix.innerText = "";
                } else {
                    discountPrefix.innerText = "";
                    discountSuffix.innerText = "%";
                }
            }

            // 監聽類型變更
            discountTypeRadios.forEach(radio => {
                radio.addEventListener("change", updateDiscountSymbol);
            });

            // 頁面加載時更新一次
            updateDiscountSymbol();
        });
    </script>
    <!-- 起訖日期限制 -->
    <script>
        document.getElementById("startDate").addEventListener("change", function() {
            document.getElementById("endDate").setAttribute("min", this.value);
        });
    </script>

    <?php include("../js.php") ?>

</body>

</html>