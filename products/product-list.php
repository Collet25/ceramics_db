<?php
require_once("../ceramics_db_connect.php");

// 分頁和顯示設定
$page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
$per_page = isset($_GET["per_page"]) ? intval($_GET["per_page"]) : 10;
$sort = isset($_GET["sort"]) ? $_GET["sort"] : "newest"; // 預設最新排序

// 篩選條件
// 只顯示未刪除的商品
$whereClause = "WHERE p.deleted_at IS NULL";
$params = [];
$types = "";

// 搜尋條件
if (isset($_GET["search"]) && $_GET["search"] !== "") {
  $whereClause .= " AND (p.name LIKE ? OR p.description LIKE ?)";
  $search = "%" . $_GET["search"] . "%";
  $params[] = $search;
  $params[] = $search;
  $types .= "ss";
}

// 分類篩選
if (isset($_GET["category"]) && $_GET["category"] !== "") {
  $whereClause .= " AND p.category = ?";
  $params[] = $_GET["category"];
  $types .= "s";
}

if (isset($_GET["subcategory"]) && $_GET["subcategory"] !== "") {
  $whereClause .= " AND p.subcategory = ?";
  $params[] = $_GET["subcategory"];
  $types .= "s";
}

// 排序條件
$orderClause = match ($sort) {
  "oldest" => "ORDER BY p.updated_at ASC",
  "price_high" => "ORDER BY p.price DESC",
  "price_low" => "ORDER BY p.price ASC",
  default => "ORDER BY p.updated_at DESC"
};

// 計算總筆數
$countSql = "SELECT COUNT(*) as total FROM products p $whereClause";
if (!empty($params)) {
  $stmt = $conn->prepare($countSql);
  $stmt->bind_param($types, ...$params);
  $stmt->execute();
  $totalResult = $stmt->get_result();
} else {
  $totalResult = $conn->query($countSql);
}
$totalRows = $totalResult->fetch_assoc()["total"];
$totalPages = ceil($totalRows / $per_page);

// 確保頁數在有效範圍內
$page = max(1, min($page, $totalPages));
$start = ($page - 1) * $per_page;

// 獲取所有分類（用於篩選器）
$sql_categories = "SELECT * FROM categories ORDER BY name";
$categories_result = $conn->query($sql_categories);

// 查詢商品資料
$sql = "SELECT p.*, c.name AS category_name, s.name AS subcategory_name,
        m.name AS material_name, o.name AS origin_name
        FROM products p
        LEFT JOIN categories c ON p.category = c.name
        LEFT JOIN subcategories s ON p.subcategory = s.name
        LEFT JOIN materials m ON p.material = m.name
        LEFT JOIN origins o ON p.origin = o.name
        $whereClause
        $orderClause
        LIMIT ?, ?";

$params[] = $start;
$params[] = $per_page;
$types .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../logo-img/head-icon.png">
  <title>
    商品管理
  </title>
  <?php include("../css.php"); ?>
  <link rel="stylesheet" href="../products/style_p.css">


  <style>
    .product-img {
      width: 100px;
      height: 100px;
      object-fit: cover;
    }

    .filter-section {
      background-color: #f8f9fa;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
    }

    .btn-icon {
      width: 32px;
      height: 32px;
      padding: 0;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 4px;
      margin: 0 2px;
    }

    .action-buttons {
      white-space: nowrap;
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

    .table th {
      text-align: center;
      vertical-align: middle;
      background-color: var(--bg-sidebar); /* 表頭背景 淺灰色 */
    }

    .table td {
      text-align: center;
      vertical-align: middle;
    }

    .table td.text-start {
      text-align: left;
    }

    .table td.product-name {
      text-align: left;
      font-weight: 500;
    }

    .table td.product-price {
      font-weight: 500;
    } 

    .filter-section {
      background-color: #F5E3C3;     /* Header按鈕 米黃色 */
    
    }

    .head-icon {
      width: 30px;
      height: 30px;
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
              <div class="page-header ">
                <div class="breadcrumb">
                  <img src="../logo-img/head-icon3.png" alt="" class="head-icon mt-2">
                  <!-- <a href="../index.php">首頁</a> / 商品管理 -->
                </div>
                <h1 class="page-title ms-2">商品列表</h1>
              </div>

            <!-- 篩選器 -->
            <div class="filter-section">
              <form class="row  mt-3 mx-2" method="GET">

                <div class="col-md-2">
                  <select class="form-select" name="category" id="category">
                    <option value="">所有分類</option>
                    <?php while ($category = $categories_result->fetch_assoc()): ?>
                      <option value="<?= $category["name"] ?>"
                        <?= isset($_GET["category"]) && $_GET["category"] === $category["name"] ? "selected" : "" ?>>
                        <?= $category["name"] ?>
                      </option>
                    <?php endwhile; ?>
                  </select>
                </div>
                <div class="col-md-2">
                  <select class="form-select" name="subcategory" id="subcategory">
                    <option value="">所有子分類</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <select class="form-select" name="sort">
                    <option value="newest" <?= $sort === "newest" ? "selected" : "" ?>>最新上架</option>
                    <option value="oldest" <?= $sort === "oldest" ? "selected" : "" ?>>最舊上架</option>
                    <option value="price_high" <?= $sort === "price_high" ? "selected" : "" ?>>價格高到低</option>
                    <option value="price_low" <?= $sort === "price_low" ? "selected" : "" ?>>價格低到高</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <select class="form-select" name="per_page">
                    <option value="10" <?= $per_page === 10 ? "selected" : "" ?>>每頁 10 筆</option>
                    <option value="20" <?= $per_page === 20 ? "selected" : "" ?>>每頁 20 筆</option>
                    <option value="50" <?= $per_page === 50 ? "selected" : "" ?>>每頁 50 筆</option>
                  </select>
                </div>

                <div class="col-md-3">
                  <input class="form-control" type="search" name="search"
                    placeholder="搜尋商品名稱或描述"
                    value="<?= isset($_GET["search"]) ? htmlspecialchars($_GET["search"]) : "" ?>">
                </div>

                <div class="col-md-1">
                  <button class="btn btn-primary w-100" type="submit">
                    <i class="bi bi-search"></i>
                  </button>
                </div>
              </form>
            </div>

            <div class="d-flex justify-content-between mb-3">
              <div>
                共 <?= $totalRows ?> 筆商品，目前顯示第 <?= $page ?> 頁，每頁 <?= $per_page ?> 筆
              </div>
              <div>
                
                <a href="product-create.php" class="btn btn-primary">
                  <i class="bi bi-plus-lg"></i> 新增商品
                </a>

                <a href="product-trash.php" class="btn btn-outline-secondary me-2">
                  <i class="bi bi-trash"></i> 回收桶
                </a>
              </div>
            </div>

            <!-- 商品列表 -->
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>圖片</th>
                    <th class="text-start">商品名稱</th>
                    <th>分類</th>
                    <th>子分類</th>
                    <th>價格</th>
                    <th>材質</th>
                    <th>產地</th>
                    <th>更新時間</th>
                    <th>操作</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                      <td><?= $row["id"] ?></td>
                      <td>
                        <img src="../uploads/<?= $row["image"] ?>"
                          alt="<?= $row["name"] ?>"
                          class="product-img">
                      </td>
                      <td class="product-name"><?= $row["name"] ?></td>
                      <td><?= $row["category_name"] ?></td>
                      <td><?= $row["subcategory_name"] ?></td>
                      <td class="product-price">NT$ <?= number_format($row["price"]) ?></td>
                      <td><?= $row["material_name"] ?></td>
                      <td><?= $row["origin_name"] ?></td>
                      <td><?= date("Y/m/d H:i", strtotime($row["updated_at"])) ?></td>
                      <td class="action-buttons">
                        <button class="btn btn-icon btn-outline-success"
                          onclick="location.href='product-view.php?id=<?= $row["id"] ?>'"
                          title="檢視">
                          <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn btn-icon btn-outline-primary"
                          onclick="location.href='product-edit.php?id=<?= $row["id"] ?>'"
                          title="編輯">
                          <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-icon btn-outline-danger delete-btn"
                          data-id="<?= $row["id"] ?>"
                          title="刪除">
                          <i class="bi bi-trash"></i>
                        </button>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>

            <!-- 分頁 -->
            <?php if ($totalPages > 1): ?>
              <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                  <?php
                  // 決定顯示的頁數範圍
                  $range = 2;
                  $start_page = max(1, $page - $range);
                  $end_page = min($totalPages, $page + $range);

                  // 生成分頁連結的基本 URL
                  $queryParams = $_GET;
                  unset($queryParams["page"]); // 移除現有的 page 參數
                  $queryString = http_build_query($queryParams);
                  $baseUrl = "?" . ($queryString ? $queryString . "&" : "");
                  ?>

                  <!-- 第一頁和上一頁 -->
                  <?php if ($page > 1): ?>
                    <li class="page-item">
                      <a class="page-link" href="<?= $baseUrl ?>page=1">首頁</a>
                    </li>
                    <li class="page-item">
                      <a class="page-link" href="<?= $baseUrl ?>page=<?= $page - 1 ?>">
                        <i class="bi bi-arrow-left"></i>
                      </a>
                    </li>
                  <?php endif; ?>

                  <!-- 頁碼 -->
                  <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                    <li class="page-item <?= $i == $page ? "active" : "" ?>">
                      <a class="page-link" href="<?= $baseUrl ?>page=<?= $i ?>"><?= $i ?></a>
                    </li>
                  <?php endfor; ?>

                  <!-- 下一頁和最後一頁 -->
                  <?php if ($page < $totalPages): ?>
                    <li class="page-item">
                      <a class="page-link" href="<?= $baseUrl ?>page=<?= $page + 1 ?>">
                        <i class="bi bi-arrow-right"></i>
                      </a>
                    </li>
                    <li class="page-item">
                      <a class="page-link" href="<?= $baseUrl ?>page=<?= $totalPages ?>">末頁</a>
                    </li>
                  <?php endif; ?>
                </ul>
              </nav>
            <?php endif; ?>
          </div>

          <!-- ******* -->





          </div>
        </div>
      </div>
    </div>



    <!-- 頁尾 -->
    <?php include("../footer.php"); ?>

  </main>

  <!-- 側邊欄 -->
  <?php //include("../aside-fixed.php"); ?>


  <?php include("../js.php"); ?>



  <!--  -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

      // 如果有選擇分類，自動載入對應的子分類
      <?php if (isset($_GET["category"]) && $_GET["category"] !== ""): ?>
        $.ajax({
          url: "get-subcategories.php",
          method: "POST",
          data: {
            category: "<?= $_GET["category"] ?>",
            selected: "<?= isset($_GET["subcategory"]) ? $_GET["subcategory"] : "" ?>"
          },
          success: function(response) {
            $("#subcategory").html(response);
          }
        });
      <?php endif; ?>

      // 刪除商品
      $(".delete-btn").click(function() {
        let id = $(this).data("id");
        if (confirm("確定要刪除這個商品嗎？")) {
          $.ajax({
            url: "product-delete.php",
            method: "POST",
            data: {
              id: id
            },
            success: function(response) {
              if (response.success) {
                alert("刪除成功");
                location.reload();
              } else {
                alert("刪除失敗：" + response.message);
              }
            },
            error: function() {
              alert("刪除失敗：系統錯誤");
            }
          });
        }
      });
    });
  </script>
</body>

</html>