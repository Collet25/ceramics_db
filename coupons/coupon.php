<?php
require_once("../ceramics_db_connect.php");

// 取得篩選條件
$conditions = ["valid=1"];
$orderClause = "";
$limitClause = "";
$filters = [];

// 取得搜尋與篩選條件
if (isset($_GET["q"])) {
    $q = $conn->real_escape_string($_GET["q"]);
    $conditions[] = "name LIKE '%$q%'";
    $filters['q'] = $q;
}

if (!empty($_GET["category"]) && $_GET["category"] !== "全部") {
    $category = $conn->real_escape_string($_GET["category"]);
    $conditions[] = "categories='$category'";
    $filters['category'] = $category;
}

if (!empty($_GET["discountType"])) {
    $discountType = $conn->real_escape_string($_GET["discountType"]);
    $conditions[] = "discountType='$discountType'";
    $filters['discountType'] = $discountType;
}

if (!empty($_GET["status"]) && $_GET["status"] !== "all") {
    $status = $conn->real_escape_string($_GET["status"]);
    $conditions[] = "status='$status'";
    $filters['status'] = $status;
}

if (!empty($_GET["startDate"]) && !empty($_GET["endDate"])) {
    $startDate = $conn->real_escape_string($_GET["startDate"]);
    $endDate = $conn->real_escape_string($_GET["endDate"]);
    $conditions[] = "startDate >= '$startDate' AND endDate <= '$endDate'";
    $filters['startDate'] = $startDate;
    $filters['endDate'] = $endDate;
} elseif (!empty($_GET["startDate"])) {
    $startDate = $conn->real_escape_string($_GET["startDate"]);
    $conditions[] = "startDate >= '$startDate'";
    $filters['startDate'] = $startDate;
}

// 取得當前排序參數
$order = isset($_GET["order"]) ? intval($_GET["order"]) : 1;

// 排序選項
$orderOptions = [
    1  => "ORDER BY id ASC",
    2  => "ORDER BY id DESC",
    3  => "ORDER BY discount ASC",
    4  => "ORDER BY discount DESC",
    5  => "ORDER BY quantity ASC",
    6  => "ORDER BY quantity DESC",
    7  => "ORDER BY startDate ASC",
    8  => "ORDER BY startDate DESC",
    9  => "ORDER BY endDate ASC",
    10 => "ORDER BY endDate DESC",
];

// 確保 order 參數合法
$orderClause = isset($orderOptions[$order]) ? $orderOptions[$order] : $orderOptions[1];

// 取得篩選後的總數量
$sqlCount = "SELECT COUNT(*) AS total FROM coupons WHERE " . implode(" AND ", $conditions);
$resultCount = $conn->query($sqlCount);
$rowCount = $resultCount->fetch_assoc();
$couponCount = $rowCount['total'];

// 分頁處理
$perPage = 10;
$page = isset($_GET["p"]) ? max(1, intval($_GET["p"])) : 1;
$totalPage = ceil($couponCount / $perPage);
$start = ($page - 1) * $perPage;
$limitClause = "LIMIT $start, $perPage";

// 組合 SQL 查詢
$sql = "SELECT * FROM coupons WHERE " . implode(" AND ", $conditions) . " $orderClause $limitClause";
$result = $conn->query($sql);
$rows = $result->fetch_all(MYSQLI_ASSOC);

// 產生篩選後的 Query String
$filters['order'] = $order;
$queryString = http_build_query($filters);

// 設定按鈕點擊後的排序值（切換升序或降序）
$nextOrder = [
    1 => 2,
    2 => 1,  // 編號
    3 => 4,
    4 => 3,  // 面額
    5 => 6,
    6 => 5,  // 數量
    7 => 8,
    8 => 7,  // 開始日期
    9 => 10,
    10 => 9 // 截止日期
];

// 取得點擊後的排序順序（確保按當前欄位正確切換）
$nextOrderId        = ($order == 1) ? 2 : 1;
$nextOrderDiscount  = ($order == 3) ? 4 : 3;
$nextOrderQuantity  = ($order == 5) ? 6 : 5;
$nextOrderStartDate = ($order == 7) ? 8 : 7;
$nextOrderEndDate   = ($order == 9) ? 10 : 9;

?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../logo-img/head-icon.png">
    <title>
        優惠券管理
    </title>
    <style>
        .d-flex-wrap {
            flex-wrap: wrap !important;
        }

        @media (max-width: 576px) {
            .input-group {
                max-width: 100%;
            }
        }
    </style>
    <?php include("../css.php") ?>
</head>

<body class="g-sidenav-show">
    <!-- aside -->
    <?php include("../aside.php") ?>

    <!-- Main content -->
    <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
        <!-- Navbar -->
        <?php include("../navbar.php") ?>

        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- 優惠券標題欄 -->
                        <div class="card-header border-bottom pb-0 shadow-sm">
                            <div class="row align-items-center g-2 pt-2">
                                <div class="d-flex justify-content-between">
                                    <div class=" mb-3">
                                        <h6 class="font-weight-bold fs-4 mb-0 ms-2"><i class=" fa-solid fa-tags"></i> 優惠券列表</h6>
                                    </div>
                                    <!-- 按鈕區 -->
                                    <div class="d-flex justify-content-end gap-2 mb-3">
                                        <a href="coupon.php?p=1&order=1" class="btn btn-cancel text-black text-center">
                                            <i class="fa-solid fa-filter"></i> 重新篩選
                                        </a>
                                        <a href="create-coupon.php" class="btn btn-primary d-flex align-items-center">
                                            <i class="fa-solid fa-plus me-2"></i> 新增優惠券
                                        </a>
                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex d-flex-wrap">
                                    <div class="h5">
                                        篩選條件
                                    </div>
                                    <!-- 按鈕 & 搜尋區 -->
                                    <div class="col-12 col-md-auto ms-auto d-flex flex-wrap align-items-center gap-2 m-0 p-0">
                                        <div class="row g-2 align-items-center">
                                            <!-- 搜尋框 -->
                                            <div class="col-12 col-sm-auto d-flex mb-3">
                                                <form action="" method="get" class="w-100">
                                                    <div class="input-group w-100">
                                                        <button class="btn btn-gray btn-outline-white" id="button-addon2" type="submit"><i class="fa-solid fa-magnifying-glass fa-fw"></i></button>
                                                        <input type="search" class="form-control" placeholder="搜尋優惠券名稱" name="q" value="<?= isset($q) ? htmlspecialchars($q, ENT_QUOTES, 'UTF-8') : '' ?>">

                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 📌 進階篩選 -->
                            <div class="card-body mt-0 p-0">
                                <form method="get" action="">
                                    <div class="my-0 py-0 row g-3 d-flex">
                                        <!-- 適用範圍 -->
                                        <div class="col-md-3 col-12 mt-0">
                                            <label class="form-label h6">適用範圍</label>
                                            <div class="d-flex flex-wrap">
                                                <div class="form-check me-2 ">
                                                    <input class="form-check-input p-1" type="radio" name="category" value="全部" id="categoryAll" <?= isset($_GET["category"]) && $_GET["category"] == "全部" ? "checked" : "" ?> checked>
                                                    <label class="form-check-label fs-6 ms-0" for="categoryAll">全部</label>
                                                </div>
                                                <div class="form-check me-2">
                                                    <input class="form-check-input p-1" type="radio" name="category" value="禮品" id="categoryGift" <?= isset($_GET["category"]) && $_GET["category"] == "禮品" ? "checked" : "" ?>>
                                                    <label class="form-check-label fs-6 ms-0" for="categoryGift">禮品</label>
                                                </div>
                                                <div class="form-check me-2">
                                                    <input class="form-check-input p-1" type="radio" name="category" value="圖書影音" id="categoryMedia" <?= isset($_GET["category"]) && $_GET["category"] == "圖書影音" ? "checked" : "" ?>>
                                                    <label class="form-check-label fs-6 ms-0" for="categoryMedia">圖書影音</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input p-1" type="radio" name="category" value="課程" id="categoryCourse" <?= isset($_GET["category"]) && $_GET["category"] == "課程" ? "checked" : "" ?>>
                                                    <label class="form-check-label fs-6 ms-0" for="categoryCourse">課程</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 折扣類型 -->
                                        <div class="col-md-3 col-12 mt-0">
                                            <label class="form-label h6">折扣類型</label>
                                            <div class="d-flex flex-wrap">
                                                <div class="form-check me-2">
                                                    <input class="form-check-input p-1" type="radio" name="discountType" value="" id="discountAll" <?= isset($_GET["discountType"]) && $_GET["discountType"] == "" ? "checked" : "" ?> checked>
                                                    <label class="form-check-label fs-6 ms-0" for="discountAll">全部</label>
                                                </div>
                                                <div class="form-check me-2">
                                                    <input class="form-check-input p-1" type="radio" name="discountType" value="百分比" id="discountPercentage" <?= isset($_GET["discountType"]) && $_GET["discountType"] == "百分比" ? "checked" : "" ?>>
                                                    <label class="form-check-label fs-6 ms-0" for="discountPercentage">百分比</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input p-1" type="radio" name="discountType" value="現金" id="discountFixed" <?= isset($_GET["discountType"]) && $_GET["discountType"] == "現金" ? "checked" : "" ?>>
                                                    <label class="form-check-label fs-6 ms-0" for="discountFixed">現金</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 狀態 -->
                                        <div class="col-md-2 col-12 mt-0">
                                            <label class="form-label h6">優惠券狀態</label>
                                            <div class="d-flex flex-wrap">
                                                <div class="form-check me-2">
                                                    <input class="form-check-input p-1" type="radio" name="status" value="all" id="statusAll" <?= isset($_GET["status"]) && $_GET["status"] == "all" ? "checked" : "" ?> checked>
                                                    <label class="form-check-label fs-6 ms-0" for="statusAll">全部</label>
                                                </div>
                                                <div class="form-check me-2">
                                                    <input class="form-check-input p-1" type="radio" name="status" value="啟用" id="statusOn" <?= isset($_GET["status"]) && $_GET["status"] == "啟用" ? "checked" : "" ?>>
                                                    <label class="form-check-label fs-6 ms-0" for="statusOn">啟用</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input p-1" type="radio" name="status" value="停用" id="statusOff" <?= isset($_GET["status"]) && $_GET["status"] == "停用" ? "checked" : "" ?>>
                                                    <label class="form-check-label fs-6 ms-0" for="statusOff">停用</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 有效日期範圍 -->

                                        <div class="col-md-4 col-12 mt-0">
                                            <div class="col-auto">
                                                <label class="form-label mb-0 h6">有效日期範圍</label>
                                            </div>
                                            <div class="row align-items-center">
                                                <div class="col-5">
                                                    <input type="date" class="form-control " id="filterStartDate" name="startDate"
                                                        value="<?= isset($_GET["startDate"]) ? htmlspecialchars($_GET["startDate"], ENT_QUOTES, 'UTF-8') : '' ?>"
                                                        style="cursor: pointer;">
                                                </div>
                                                <div class="col-1"> 至</div>
                                                <div class="col-5">
                                                    <input type="date" class="form-control" id="filterEndDate" name="endDate"
                                                        value="<?= isset($_GET["endDate"]) ? htmlspecialchars($_GET["endDate"], ENT_QUOTES, 'UTF-8') : '' ?>"
                                                        style="cursor: pointer;">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 按鈕和篩選結果數量並排 -->
                                        <div class="d-flex col-12 align-items-end">
                                            <!-- 篩選結果數量置中 -->
                                            <div class="d-flex flex-grow-1 justify-content-center align-items-center">
                                                <strong class="h6" id="filterResultCount">顯示結果：共 <?= $couponCount ?> 筆 </strong>
                                            </div>
                                            <!-- 頁數資訊置底 -->
                                            <div class="d-flex justify-content-end align-items-end">
                                                <strong class="h6">每頁10筆， <?= $page ?> / <?= $totalPage ?> 頁</strong>
                                            </div>
                                        </div>

                                    </div>
                            </div>
                            </form>
                        </div>


                        <!-- 📌 優惠券列表 -->
                        <div class="table-responsive p-0">
                            <?php if ($couponCount > 0): ?>
                                <table class="table table-striped table-hover align-items-center justify-content-center mb-0" style="border-radius: 0px;">
                                    <thead class="text-center">
                                        <tr>
                                            <th class="fs-6">
                                                <a class="text-decoration-none text-light" href="coupon.php?<?= $queryString ?>&order=<?= $nextOrderId ?>">
                                                    ID
                                                    <i class="fa-solid <?= ($order == 1) ? 'fa-sort-up' : (($order == 2) ? 'fa-sort-down' : 'fa-sort') ?>"></i>
                                                </a>
                                            </th>
                                            <th class="text-light fs-6">名稱</th>
                                            <th class="text-light fs-6">折扣碼</th>
                                            <th id="category" name="category" class="text-light fs-6">
                                                適用範圍
                                            </th>
                                            <!-- <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">類型</th> -->
                                            <th class="text-light fs-6">
                                                <a class="text-decoration-none text-light" href="coupon.php?<?= $queryString ?>&order=<?= $nextOrderDiscount ?>">
                                                    折扣
                                                    <i class="fa-solid <?= ($order == 3) ? 'fa-sort-up' : (($order == 4) ? 'fa-sort-down' : 'fa-sort') ?>"></i>
                                                </a>
                                            </th>
                                            <th class="text-light text-s font-weight-semibold">
                                                <a class="text-decoration-none text-light" href="coupon.php?<?= $queryString ?>&order=<?= $nextOrderQuantity ?>">
                                                    數量(張)
                                                    <i class="fa-solid <?= ($order == 5) ? 'fa-sort-up' : (($order == 6) ? 'fa-sort-down' : 'fa-sort') ?>"></i>
                                                </a>
                                            </th>
                                            <th class="text-light text-s font-weight-semibold"><a class="text-decoration-none text-light" href="coupon.php?<?= $queryString ?>&order=<?= $nextOrderStartDate ?>">
                                                    開始日期
                                                    <i class="fa-solid <?= ($order == 7) ? 'fa-sort-up' : (($order == 8) ? 'fa-sort-down' : 'fa-sort') ?>"></i>
                                                </a></th>
                                            <th class="text-light  font-weight-semibold"><a class="text-decoration-none text-light" href="coupon.php?<?= $queryString ?>&order=<?= $nextOrderEndDate ?>">
                                                    截止日期
                                                    <i class="fa-solid <?= ($order == 9) ? 'fa-sort-up' : (($order == 10) ? 'fa-sort-down' : 'fa-sort') ?>"></i>
                                                </a></th>
                                            <th class="text-light text-s font-weight-semibold">狀態</th>
                                            <th class="text-light text-s font-weight-semibold">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        <?php foreach ($rows as $row): ?>
                                            <tr>
                                                <td class="text-center"><?= $row["id"] ?></td>
                                                <td><?= $row["name"] ?></td>
                                                <td><?= $row["code"] ?></td>
                                                <td><?= $row["categories"] ?></td>
                                                <td class="d-none"><?= $row["discountType"] ?></td>
                                                <td><?= $row["discount"] ?></td>
                                                <td> <?= number_format($row["quantity"]) ?></td>
                                                <td><?= $row["startDate"] ?></td>
                                                <td><?= $row["endDate"] ?></td>
                                                <td><?php if ($row['status'] == '啟用'): ?>
                                                        <strong class="text-dark">啟用</span>
                                                        <?php else: ?>
                                                            <strong class="text-white btn btn-sm bg-danger">停用</strong>
                                                        <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a class="btn btn-outline-success" href="coupon-detail.php?id=<?= $row["id"] ?>">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>
                                                    <a class="btn btn-outline-dark" href="coupon-edit.php?id=<?= $row["id"] ?>">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </a>
                                                </td>

                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <p class="text-muted">⚠ 找不到符合條件的優惠券，請重新篩選。</p>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

                <!-- 分頁元件 -->
                <?php if ($totalPage > 1): ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination d-flex justify-content-end mt-3">
                            <?php for ($i = 1; $i <= $totalPage; $i++): ?>
                                <li class="page-item <?= ($i == $page) ? "active" : "" ?>">
                                    <a class="page-link" href="coupon.php?p=<?= $i ?>&<?= $queryString ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
        <!-- footer -->

        <?php include("../footer.php") ?>
    </main>



    <!-- 頁數跳轉 -->
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>

    <!-- 動態替換$ & % -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 選取所有的表格行
            document.querySelectorAll("tbody tr").forEach(function(row) {
                let discountType = row.cells[4].innerText.trim(); // 折扣類型
                let discountCell = row.cells[5]; // 折扣數值的儲存格

                // 解析數值
                let discountValue = parseFloat(discountCell.innerText.trim());

                // 判斷折扣類型並加上符號
                if (discountType === "現金") {
                    discountCell.innerText = `$${discountValue}`;
                } else if (discountType === "百分比") {
                    discountCell.innerText = `${discountValue}%`;
                }
            });
        });
    </script>
    <!-- 起訖日期限制 -->
    
    <!-- 選取後自動提交表單 -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let filters = document.querySelectorAll("input[type='radio'], input[type='date']");
            filters.forEach(function(filter) {
                filter.addEventListener("change", function() {
                    this.form.submit();
                });
            });
        });
    </script>



    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Corporate UI Dashboard: parallax effects, scripts for the example pages etc -->
    <script>
        let coupons = <?= json_encode($row, JSON_UNESCAPED_UNICODE); ?>;
        console.log(coupons);
    </script>



    <?php include("../js.php") ?>
</body>

</html>