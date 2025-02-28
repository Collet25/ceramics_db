<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新增優惠券</title>
    <style>

    </style>
    <?php include("../css.php") ?>
</head>

<body class="g-sidenav-show overflow-hidden">
    <!-- aside -->
    <?php include("../aside.php") ?>

    <!-- Main content -->
    <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
        <!-- Navbar -->
        <?php include("../navbar.php") ?>

        <div class="container-fluid mt-2">
            <!-- 返回管理頁面連結 -->
            <div class="row justify-content-center mt-4">
                <div class="col-md-8 col-lg-6">
                    <div class="card shadow-lg border-0 rounded-3">
                        <div class="card-header text-white text-center shadow-sm position-relative d-flex justify-content-center align-items-center">
                            
                            <h4 class="mb-0 text-dark">新增優惠券</h4>
                        </div>

                        <div class="card-body p-4">
                            <form action="doCreate.php" method="post">

                                <!-- 優惠券名稱 -->
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-semibold">名稱</label>
                                    <input type="text" id="name" name="name" class="form-control" required>
                                </div>

                                <!-- 折扣碼 + 生成按鈕 -->
                                <div class="mb-3">
                                    <label for="code" class="form-label fw-semibold">折扣碼</label>
                                    <div class="input-group">
                                        <input type="text" id="code" name="code" class="form-control" required>
                                        <button type="button" class="btn btn-primary" onclick="generateUUID()">自動生成</button>
                                    </div>
                                </div>

                                <!-- 適用範圍 -->
                                <div class="mb-3">
                                    <label for="categories" class="form-label fw-semibold">適用範圍</label>
                                    <select id="categories" name="categories" class="form-select" required>
                                        <option value="全部">全部</option>
                                        <option value="禮品">禮品</option>
                                        <option value="圖書影音">圖書影音</option>
                                        <option value="課程">課程</option>
                                    </select>
                                </div>

                                <!-- 折扣類型 -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">折扣</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="discountType" id="percentage" value="百分比" checked>
                                            <label class="form-check-label" for="percentage">百分比折扣</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="discountType" id="fixed" value="現金">
                                            <label class="form-check-label" for="fixed">現金折扣</label>
                                        </div>
                                    </div>
                                    <div class="input-group mt-2">
                                        <span class="input-group-text" id="discountPrefix">$</span>
                                        <input type="number" id="discount" name="discount" class="form-control" step="0.01" min="0" required>
                                        <span class="input-group-text" id="discountSuffix">%</span>
                                    </div>
                                </div>

                                <!-- 最低消費金額 -->
                                <div class="mb-3">
                                    <label for="minSpend" class="form-label fw-semibold">最低消費金額</label>
                                    <input type="number" id="minSpend" name="minSpend" class="form-control" min="0">
                                </div>

                                <!-- 可使用次數 -->
                                <div class="mb-3">
                                    <label for="quantity" class="form-label fw-semibold">流通數量</label>
                                    <input type="number" id="quantity" name="quantity" class="form-control" min="1" value="1" required>
                                </div>

                                <!-- 開始日期 -->
                                <div class="mb-3">
                                    <label for="startDate" class="form-label fw-semibold">開始日期</label>
                                    <input type="date" id="startDate" name="startDate" class="form-control" required>
                                </div>

                                <!-- 結束日期 -->
                                <div class="mb-3">
                                    <label for="endDate" class="form-label fw-semibold">結束日期</label>
                                    <input type="date" id="endDate" name="endDate" class="form-control" required>
                                </div>

                                <!-- 是否啟用 -->
                                <div class="mb-3">
                                    <label for="valid" class="form-label fw-semibold">優惠券狀態</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" id="enabled" value="啟用" checked>
                                            <label class="form-check-label" for="enabled">啟用</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" id="disabled" value="停用">
                                            <label class="form-check-label" for="disabled">停用</label>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <!-- 提交按鈕 -->
                                <div class="d-flex justify-content-between">
                                    <a href="coupon.php" class="btn btn-cancel">
                                        <i class="fa-solid fa-circle-left"></i> 返回
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa-solid fa-plus"></i> 新增
                                    </button>
                                </div>

                        </div>
                    </div>
                    </form>
                </div>
                <!-- footer -->
                <?php include("../footer.php") ?>
            </div>
        </div>

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
        document.getElementById("startDate").addEventListener("change", function() {
            document.getElementById("endDate").setAttribute("min", this.value);
        });
    </script>

    <?php include("../js.php") ?>
</body>

</html>