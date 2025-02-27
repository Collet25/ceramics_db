<?php
require_once("../ceramics_db_connect.php");

// 獲取所有分類
$sql_categories = "SELECT * FROM categories ORDER BY name";
$categories_result = $conn->query($sql_categories);

// 獲取所有材質
$sql_materials = "SELECT * FROM materials ORDER BY name";
$materials_result = $conn->query($sql_materials);

// 獲取所有產地
$sql_origins = "SELECT * FROM origins ORDER BY name";
$origins_result = $conn->query($sql_origins);

?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../logo-img/head-icon.png">
  <title>
    新增商品
  </title>
  <?php include("../css.php"); ?>
  <style>
    .preview-image {
      max-width: 100%;
      height: auto;
      margin-top: 10px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .image-section {
      background-color: #f8f9fa;
      padding: 20px;
      border-radius: 8px;
      height: 100%;
      text-align: center;
    }

    .form-section {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .page-header {
      margin-bottom: 2rem;
    }

    .breadcrumb {
      font-size: 0.9rem;
      color: #6c757d;
      margin-bottom: 0.5rem;
    }

    .breadcrumb a {
      color: #6c757d;
      text-decoration: none;
    }

    .breadcrumb a:hover {
      color: #0d6efd;
    }

    .page-title {
      font-size: 1.75rem;
      font-weight: bold;
      margin: 0;
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
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="mb-4">

            <!-- ******商品列表********* -->

            <div class="container">
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
                  <h1 class="page-title ms-3">新增商品</h1>
                </div>
                
              </div>

              <form action="handle-create.php" method="POST" enctype="multipart/form-data">
                <div class="row">
                  <!-- 左側圖片區 -->
                  <div class="col-md-4">
                    <div class="image-section">
                      <div class="mb-3">
                        <label for="image" class="form-label fw-bold">商品圖片</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                        <div class="mt-3">
                          <label class="form-label">圖片預覽</label>
                          <div class="text-center">
                            <img id="preview" class="preview-image" style="display: none;">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- 右側表單區 -->
                  <div class="col-md-8">
                    <div class="form-section">
                      <div class="mb-3">
                        <label for="name" class="form-label">商品名稱</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                      </div>

                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <label for="category" class="form-label">主分類</label>
                          <select class="form-select" id="category" name="category" required>
                            <option value="">請選擇分類</option>
                            <?php while ($row = $categories_result->fetch_assoc()): ?>
                              <option value="<?= $row["name"] ?>"><?= $row["name"] ?></option>
                            <?php endwhile; ?>
                          </select>
                        </div>

                        <div class="col-md-6 mb-3">
                          <label for="subcategory" class="form-label">子分類</label>
                          <select class="form-select" id="subcategory" name="subcategory" required>
                            <option value="">請先選擇主分類</option>
                          </select>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <label for="material" class="form-label">材質</label>
                          <select class="form-select" id="material" name="material" required>
                            <option value="">請選擇材質</option>
                            <?php while ($row = $materials_result->fetch_assoc()): ?>
                              <option value="<?= $row["name"] ?>"><?= $row["name"] ?></option>
                            <?php endwhile; ?>
                          </select>
                        </div>

                        <div class="col-md-6 mb-3">
                          <label for="origin" class="form-label">產地</label>
                          <select class="form-select" id="origin" name="origin" required>
                            <option value="">請選擇產地</option>
                            <?php while ($row = $origins_result->fetch_assoc()): ?>
                              <option value="<?= $row["name"] ?>"><?= $row["name"] ?></option>
                            <?php endwhile; ?>
                          </select>
                        </div>
                      </div>

                      <div class="mb-3">
                        <label for="price" class="form-label">價格</label>
                        <input type="number" class="form-control" id="price" name="price" min="0" required>
                      </div>

                      <div class="mb-3">
                        <label for="description" class="form-label">商品描述</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                      </div>

                      <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                          <i class="bi bi-check-lg"></i> 儲存
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="confirmCancel()">
                          <i class="bi bi-x-lg"></i> 取消
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>

            <!-- ************ -->




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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
      // 當主分類改變時，更新子分類
      $("#category").change(function() {
        let category = $(this).val();
        $.ajax({
          url: "get-subcategories.php",
          method: "POST",
          data: {
            category: category
          },
          success: function(response) {
            $("#subcategory").html(response);
          }
        });
      });

      // 圖片預覽
      $("#image").change(function() {
        if (this.files && this.files[0]) {
          let reader = new FileReader();
          reader.onload = function(e) {
            $("#preview").attr('src', e.target.result).show();
          }
          reader.readAsDataURL(this.files[0]);
        }
      });
    });

    function confirmCancel() {
      if (confirm('確定要取消嗎？已輸入的資料將會遺失')) {
        window.location.href = 'product-list.php';
      }
    }
  </script>
</body>

</html>