<?php
require_once("../ceramics_db_connect.php");
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新增場地</title>
    <?php include("../css.php"); ?> <!-- 引入 CSS -->
    <?php include("../ev-css.php"); ?>
</head>

<body class="g-sidenav-show">
    <?php include("../aside.php"); ?> <!-- 側邊欄 -->
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Navbar -->
        <?php include("../navbar.php"); ?>

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="mb-4">

                        <!-- ******商品列表********* -->

                        <div class="container">
                            <div class="page-header">
                                <div class="breadcrumb">
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <h2>新增場地</h2>
                                    </div>
                                    <div class="card-body">
                                        <form action="venue-handle-create.php" method="POST" enctype="multipart/form-data">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">場地名稱</label>
                                                <input type="text" class="form-control" id="name" name="name" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="image" class="form-label">場地圖片</label>
                                                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                                                <img id="preview" class="img-thumbnail mt-3" style="display: none; max-width: 200px;">
                                            </div>

                                            <div class="mb-3">
                                                <label for="description" class="form-label">場地描述</label>
                                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label for="category" class="form-label">場地類別</label>
                                                <br>
                                                <form>
                                                    <input class="ms-2" type="radio" id="exhibition" name="category" value="exhibition">
                                                    <label for="exhibition">展覽廳</label>
                                                    <input class="ms-2" type="radio" id="room" name="category" value="room">
                                                    <label for="room">教室</label><br>
                                                </form>


                                                <div class="mb-3">
                                                    <label for="capacity" class="form-label">容納人數</label>
                                                    <input type="number" class="form-control" id="capacity" name="capacity" min="1" required>
                                                </div>

                                                <button type="submit" class="btn btn-primary">新增場地</button>
                                                <a href="venue-list.php" class="btn btn-secondary">取消</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include("../footer.php"); ?>

    </main>


    <?php include("../js.php"); ?>

    <script>
        document.getElementById("image").addEventListener("change", function(event) {
            let reader = new FileReader();
            reader.onload = function(e) {
                let preview = document.getElementById("preview");
                preview.src = e.target.result;
                preview.style.display = "block";
            }
            reader.readAsDataURL(event.target.files[0]);
        });
    </script>

</body>

</html>