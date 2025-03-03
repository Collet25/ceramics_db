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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      cursor: url("https://abs.twimg.com/a/1446542199/img/t1/web_heart_animation.png") 16 16, auto;
    }

    .product-image-container {
      position: relative;
      width: 100%;
      height: 100%;
      padding: 20px;
      text-align: center;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(145deg, #ffffff, #f5f5f5);
      box-shadow: 0 10px 30px rgba(154, 52, 18, 0.08);
      border: 1px solid rgba(154, 52, 18, 0.05);
      border-radius: 15px;
      transition: all 0.3s ease;
    }

    .product-image-container:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(154, 52, 18, 0.1);
    }

    .preview-image {
      max-width: 100%;
      height: auto;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(154, 52, 18, 0.1);
      transition: all 0.3s ease;
    }

    .preview-image:hover {
      transform: scale(1.02);
      box-shadow: 0 8px 25px rgba(154, 52, 18, 0.15);
    }

    .image-section {
      background: linear-gradient(165deg, rgba(255, 245, 231, 0.95), rgba(245, 227, 195, 0.95));
      padding: 25px;
      border-radius: 25px;
      height: 100%;
      box-shadow: 0 0 20px rgba(154, 52, 18, 0.05);
    }

    .form-section {
      background: linear-gradient(165deg, rgba(255, 245, 231, 0.95), rgba(245, 227, 195, 0.95));
      padding: 25px;
      border-radius: 25px;
      position: relative;
      box-shadow: 0 0 20px rgba(154, 52, 18, 0.05);
    }

    .form-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: radial-gradient(circle at top right, rgba(234, 88, 12, 0.1), transparent 70%);
      pointer-events: none;
      border-radius: 25px;
    }

    .page-header {
      position: relative;
      padding-bottom: 1rem;
      margin-bottom: 2rem;
    }

    .page-header::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 2px;
      background: linear-gradient(90deg, #9A3412, #EA580C);
      border-radius: 2px;
    }

    .breadcrumb {
      font-size: 0.9rem;
      color: #6c757d;
      margin-bottom: 0.5rem;
    }

    .breadcrumb a {
      color: #6c757d;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .breadcrumb a:hover {
      color: #9A3412;
    }

    .page-title {
      font-size: 2rem;
      font-weight: 600;
      color: #9A3412;
      margin-bottom: 0.5rem;
    }

    .form-label {
      font-weight: 600;
      color: #9A3412;
      font-size: 1rem;
      letter-spacing: 0.5px;
      margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
      border: 1px solid rgba(154, 52, 18, 0.2);
      border-radius: 10px;
      padding: 0.6rem 1rem;
      transition: all 0.3s ease;
      background-color: rgba(255, 255, 255, 0.9);
    }

    .form-control:focus, .form-select:focus {
      border-color: #9A3412;
      box-shadow: 0 0 0 0.2rem rgba(154, 52, 18, 0.15);
    }

    .btn-primary {
      background: linear-gradient(135deg, #9A3412, #EA580C);
      border: none;
      padding: 0.6rem 1.2rem;
      border-radius: 10px;
      font-weight: 500;
      transition: all 0.3s ease;
      color: white;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(154, 52, 18, 0.3);
      background: linear-gradient(135deg, #EA580C, #9A3412);
    }

    .btn-outline-secondary {
      border: 2px solid #9A3412;
      color: #9A3412;
      padding: 0.6rem 1.2rem;
      border-radius: 10px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
      background: #9A3412;
      color: #fff;
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(154, 52, 18, 0.2);
    }

    .form-text {
      color: #9A3412;
      font-size: 0.85rem;
      margin-top: 0.25rem;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* 響應式優化 */
    @media (max-width: 768px) {
      .form-section, .image-section {
        margin-top: 1.5rem;
        padding: 1.2rem;
      }
      
      .form-label {
        font-size: 0.9rem;
      }
      
      .preview-image {
        max-width: 100%;
      }
      
      .page-title {
        font-size: 1.5rem;
      }
    }

    .swal2-popup {
      border-radius: 15px;
    }

    .swal2-title {
      color: #9A3412 !important;
    }

    .swal2-confirm {
      background: linear-gradient(135deg, #9A3412, #EA580C) !important;
    }

    .swal2-cancel {
      background: #dc3545 !important;
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

              <form id="createForm" action="handle-create.php" method="POST" enctype="multipart/form-data">
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
                        <button type="button" class="btn btn-primary" onclick="confirmSubmit()">
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

    function confirmSubmit() {
      Swal.fire({
        title: '確定要儲存嗎？',
        text: '請確認商品資料是否正確',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#9A3412',
        cancelButtonColor: '#d33',
        confirmButtonText: '儲存',
        cancelButtonText: '返回'
      }).then((result) => {
        if (result.isConfirmed) {
          // 使用FormData获取表单数据
          let formData = new FormData(document.getElementById('createForm'));
          
          // 使用AJAX提交表单
          $.ajax({
            url: "handle-create.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
              try {
                let result = JSON.parse(response);
                if(result.success) {
                  // 成功时显示成功消息
                  Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: '商品新增成功',
                    text: result.message,
                    showConfirmButton: false,
                    timer: 1500,
                    backdrop: `rgba(0,0,0,0.4)`,
                    background: '#fff',
                    customClass: {
                      title: 'text-orange-700',
                      popup: 'rounded-lg shadow-xl'
                    }
                  }).then(() => {
                    window.location.href = 'product-list.php';
                  });
                } else {
                  // 失败时显示错误消息
                  Swal.fire({
                    icon: 'error',
                    title: '新增失敗',
                    text: result.message,
                    confirmButtonColor: '#9A3412'
                  });
                }
              } catch(e) {
                console.error('JSON parsing error:', e);
                Swal.fire({
                  icon: 'error',
                  title: '系統錯誤',
                  text: '資料處理失敗',
                  confirmButtonColor: '#9A3412'
                });
              }
            },
            error: function(xhr, status, error) {
              Swal.fire({
                icon: 'error',
                title: '系統錯誤',
                text: '儲存過程發生錯誤',
                confirmButtonColor: '#9A3412'
              });
            }
          });
        }
      });
    }

    function confirmCancel() {
      Swal.fire({
        title: '確定要取消嗎？',
        text: '已輸入的資料將會遺失',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#9A3412',
        cancelButtonColor: '#d33',
        confirmButtonText: '確定',
        cancelButtonText: '返回'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = 'product-list.php';
        }
      });
    }
  </script>
</body>

</html>