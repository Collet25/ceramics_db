<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../logo-img/head-icon.png">
    <title>新增優惠券</title>

    <?php include("../css.php") ?>
</head>

<body class="g-sidenav-show overflow-hidden">
    <!-- aside -->
    <?php include("../aside.php") ?>

    <!-- Main content -->
    <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
        <!-- Navbar -->
        <?php include("../navbar.php") ?>

        <div class="container-fluid py-4 px-5">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-sm-12 col-md-10 col-lg-8">
                    <div class="card">
                        <!-- 優惠券標題欄 -->
                        <div class="card-header border-bottom pb-0">
                            <div class="row align-items-center g-2 pt-2">
                                <div class="d-flex justify-content-between">
                                    <div class="mb-3">
                                        <h6 class="font-weight-bold fs-4 mb-0 ms-2">
                                            <i class="fa-solid fa-tags"></i> 新增優惠券
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body mt-3 table-responsive">
                            <form action="doCreate.php" method="post">
                                <table class="table table-borderless table-sm " style="border-radius: 0px;">
                                    <tbody>
                                        <tr>
                                            <th class="text-center align-middle bg-gray-100">名稱</th>
                                            <td><input type="text" name="name" class="form-control" placeholder="請輸入4~30字" required></td>
                                            <th class="text-center align-middle bg-gray-100">折扣碼</th>
                                            <td>
                                                <div class="input-group">
                                                    <input type="text" id="code" name="code" class="form-control" required>
                                                    <button type="button" class="btn btn-info" onclick="generateUUID()">自動生成</button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center align-middle bg-gray-100">適用範圍</th>
                                            <td>
                                                <select name="categories" class="form-select" required>
                                                    <option value="禮品">禮品</option>
                                                    <option value="圖書影音">圖書影音</option>
                                                    <option value="課程">課程</option>
                                                </select>
                                            </td>
                                            <th class="text-center align-middle bg-gray-100">最低消費金額</th>
                                            <td><input type="number" name="minSpend" class="form-control" min="0" placeholder="0"></td>
                                        </tr>
                                        <tr>
                                            <th class="text-center align-middle bg-gray-100">折扣類型</th>
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input p-1" type="radio" id="percentage" name="discountType" value="百分比" checked>
                                                    <label class="form-check-label" for="percentage">百分比折扣</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input p-1" type="radio" id="fixed" name="discountType" value="現金">
                                                    <label class="form-check-label" for="fixed">現金折扣</label>
                                                </div>
                                            </td>
                                            <th class="text-center align-middle bg-gray-100">面額</th>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="discountPrefix">$</span>
                                                    <input type="number" id="discount" name="discount" class="form-control" step="0.01" min="0" required>
                                                    <span class="input-group-text" id="discountSuffix">%</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center align-middle bg-gray-100">開始日期</th>
                                            <td><input type="date" id="startDate" name="startDate" class="form-control" required></td>
                                            <th class="text-center align-middle bg-gray-100">結束日期</th>
                                            <td><input type="date" id="endDate" name="endDate" class="form-control" required></td>
                                        </tr>
                                        <tr>
                                            <th class="text-center align-middle bg-gray-100">流通數量</th>
                                            <td><input type="number" name="quantity" class="form-control" min="1" value="1" required></td>
                                            <th class="text-center align-middle bg-gray-100">優惠券狀態</th>
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input p-1" type="radio" id="statusActive" name="status" value="啟用" checked>
                                                    <label class="form-check-label" for="statusActive">啟用</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input p-1" type="radio" id="statusInactive" name="status" value="停用">
                                                    <label class="form-check-label" for="statusInactive">停用</label>
                                                </div>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary">
                                       確認
                                    </button>
                                    <a href="coupon.php" class="btn btn-cancel">
                                        取消
                                    </a>
                                    
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include("../footer.php") ?>

    </main>

    <script>
        // 生成 UUID 並填入折扣碼欄位
        function generateUUID() {
            const codeField = document.getElementById("code");
            codeField.value = crypto.randomUUID().slice(0, 8).toUpperCase();
        }
    </script>

    <!-- 動態修改%$呈現 -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const discountInput = document.getElementById("discount");
            const percentageRadio = document.getElementById("percentage");
            const fixedRadio = document.getElementById("fixed");
            const discountPrefix = document.getElementById("discountPrefix");
            const discountSuffix = document.getElementById("discountSuffix");

            function updateDiscountUI() {
                if (percentageRadio.checked) {
                    discountInput.setAttribute("step", "0.1");
                    discountInput.setAttribute("min", "0");
                    discountInput.setAttribute("max", "100");
                    discountSuffix.style.display = "inline-block";
                    discountPrefix.style.display = "none";
                } else if (fixedRadio.checked) {
                    discountInput.setAttribute("step", "1");
                    discountInput.setAttribute("min", "1");
                    discountInput.removeAttribute("max");
                    discountPrefix.style.display = "inline-block";
                    discountSuffix.style.display = "none";
                }
            }

            percentageRadio.addEventListener("change", updateDiscountUI);
            fixedRadio.addEventListener("change", updateDiscountUI);

            // 預設狀態
            updateDiscountUI();
        });
    </script>

    <!-- 起訖日期限制 -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const startDate = document.getElementById("startDate");
            const endDate = document.getElementById("endDate");

            startDate.addEventListener("change", function() {
                endDate.setAttribute("min", this.value);
            });
        });
    </script>


    <?php include("../js.php") ?>
</body>

</html>