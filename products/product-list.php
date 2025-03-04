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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- 噴射動畫 -->
  <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.3.2"></script>


  <style>
    body {
    cursor: url("https://abs.twimg.com/a/1446542199/img/t1/web_heart_animation.png") 16 16, auto;
    }

    .product-img {
      width: 100px;
      height: 100px;
      object-fit: cover;
    }

    .filter-section {
      background: linear-gradient(135deg, #F5E3C3 0%, #FFF5E7 100%);
      padding: 25px 20px;
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(154, 52, 18, 0.08);
      margin-bottom: 30px;
      border: 1px solid rgba(154, 52, 18, 0.1);
    }

    .filter-section .row {
      align-items: center;
    }

    .filter-section .form-select,
    .filter-section .form-control {
        background-color: rgba(255, 255, 255, 0.9);
        border: 2px solid rgba(154, 52, 18, 0.15);
        border-radius: 10px;
        height: 45px;
        font-size: 0.95rem;
        color: #9A3412;
        transition: all 0.3s ease;
        padding: 0 15px;
        line-height: 41px; /* 45px - 2px * 2 (border) */
    }

    .filter-section .form-select {
        padding-right: 2.5rem;
    }

    /* 重設下拉選單樣式 */
    .filter-section select.form-select {
        text-align: left;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    /* 選項樣式重設 */
    .filter-section select.form-select option {
        padding: 8px 15px;
        height: auto;
        line-height: 1.5;
        background-color: #fff;
        color: #9A3412;
        font-size: 0.95rem;
    }

    /* 下拉選單展開時的選項樣式 */
    .filter-section select.form-select:focus option {
        background-color: #fff;
    }

    .filter-section select.form-select option:hover,
    .filter-section select.form-select option:focus,
    .filter-section select.form-select option:active {
        background-color: rgba(154, 52, 18, 0.1);
    }

    /* 移除預設的下拉箭頭並自定義 */
    .filter-section .form-select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%239A3412' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 12px;
    }

    /* 確保選項容器有足夠的空間 */
    .filter-section select.form-select optgroup,
    .filter-section select.form-select option {
        margin: 4px 0;
        padding: 8px 15px;
    }

    /* 修正Firefox特定的樣式問題 */
    @-moz-document url-prefix() {
        .filter-section select.form-select option {
            padding: 8px 15px;
            line-height: 1.5;
        }
    }

    /* 修正Chrome特定的樣式問題 */
    @media screen and (-webkit-min-device-pixel-ratio:0) {
        .filter-section select.form-select option {
            padding: 8px 15px;
            line-height: 1.5;
        }
    }

    /* 修正Safari特定的樣式問題 */
    @supports (-webkit-appearance: none) {
        .filter-section select.form-select option {
            padding: 8px 15px;
            line-height: 1.5;
        }
    }

    .filter-label {
      font-size: 0.9rem;
      color: #9A3412;
      margin-bottom: 8px;
      font-weight: 500;
      display: block;
    }

    /* 搜尋按鈕優化 */
    .search-btn-effect {
      background: #9A3412;
      border: none;
      height: 45px;
      border-radius: 10px;
      color: #fff;
      font-size: 1.1rem;
      transition: all 0.3s ease;
      margin-top: 0;
    }

    .search-btn-effect:hover {
      background: #EA580C;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(154, 52, 18, 0.2);
    }

    .search-btn-effect:active {
      transform: translateY(0);
    }

    .search-btn-effect.clicked {
      transform: scale(0.95);
      opacity: 0.9;
    }

    /* 搜尋框優化 */
    .filter-section .form-control::placeholder {
      color: #9CA3AF;
      opacity: 0.7;
    }

    /* 響應式優化 */
    @media (max-width: 768px) {
      .filter-section {
        padding: 20px 15px;
      }
      
      .filter-section .row > div {
        margin-bottom: 15px;
      }

      .search-btn-effect {
        margin-top: 0;
      }
    }

    /* 分類區塊容器優化 */
    .filter-container {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
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

    /* 按下去發光 */
    .glow-button {
    padding: 10px 20px;
    border: none;
    /* background-color: #ffcc00; */
    font-size: 16px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .glow-button::after {
    content: "";
    position: absolute;
    width: 200%;
    height: 200%;
    top: -50%;
    left: -50%;
    background: radial-gradient(circle, rgba(255,255,255,0.8) 10%, transparent 60%);
    transform: scale(0);
    transition: transform 0.3s;
  }
  .glow-button:active::after {
    transform: scale(1);
    transition: transform 0s;
  }

    /* 添加動畫效果 */
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* 按鈕懸停效果 */
    .btn-icon {
        transition: transform 0.2s ease;
    }

    .btn-icon:hover {
        transform: scale(1.1);
    }

    /* 表格行懸停效果 */
    .table tbody tr {
        transition: all 0.3s ease;
    }
    
    .table tbody tr:hover {
        background-color: rgba(245, 227, 195, 0.2) !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    /* 商品圖片懸停效果 */
    .product-img {
        transition: transform 0.3s ease;
    }
    
    .product-img:hover {
        transform: scale(1.2);
    }

    /* 搜尋按鈕點擊效果 */
    .search-btn-effect {
        position: relative;
        overflow: hidden;
    }

    .search-btn-effect:after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 5px;
        height: 5px;
        background: rgba(255, 255, 255, .5);
        opacity: 0;
        border-radius: 100%;
        transform: scale(1, 1) translate(-50%);
        transform-origin: 50% 50%;
    }

    .search-btn-effect:focus:not(:active)::after {
        animation: ripple 1s ease-out;
    }

    @keyframes ripple {
        0% {
            transform: scale(0, 0);
            opacity: 0.5;
        }
        100% {
            transform: scale(100, 100);
            opacity: 0;
        }
    }

    /* 整體頁面優化 */
    .container-fluid {
      padding: 2rem;
    }

    /* 頁面標題優化 */
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

    .page-title {
      font-size: 2rem;
      font-weight: 600;
      color: #9A3412;
      margin-bottom: 0.5rem;
    }

    /* 表格優化 */
    .table {
      background: #fff;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 0 20px rgba(154, 52, 18, 0.05);
      width: 100%;
    }

    .table thead {
      background: linear-gradient(90deg, #9A3412, #EA580C);
      width: 100%;
    }

    .table thead tr {
      background: none;
    }

    .table thead th {
      color: #fff;
      font-weight: 500;
      padding: 1rem;
      border: none;
      font-size: 0.95rem;
      background: none;
    }

    .table tbody tr {
      transition: all 0.3s ease;
      border-bottom: 1px solid rgba(154, 52, 18, 0.1);
      width: 100%;
    }

    .table tbody tr td {
      border-bottom: 1px solid rgba(154, 52, 18, 0.1);
    }

    .table tbody tr:last-child td {
      border-bottom: none;
    }

    .table-responsive {
      border-radius: 15px;
      overflow: hidden;
    }

    /* 商品圖片優化 */
    .product-img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
    }

    .product-img:hover {
      transform: scale(1.5);
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    /* 按鈕優化 */
    .btn-primary {
      background: linear-gradient(135deg, #9A3412, #EA580C);
      border: none;
      padding: 0.6rem 1.2rem;
      border-radius: 10px;
      font-weight: 500;
      transition: all 0.3s ease;
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

    /* 操作按鈕優化 */
    .action-buttons .btn-icon {
      width: 35px;
      height: 35px;
      padding: 0;
      border-radius: 8px;
      margin: 0 3px;
      transition: all 0.3s ease;
    }

    .action-buttons .btn-icon:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .btn-outline-success {
      color: #10B981;
      border-color: #10B981;
    }

    .btn-outline-success:hover {
      background: #10B981;
      color: #fff;
    }

    .btn-outline-primary {
      color: #3B82F6;
      border-color: #3B82F6;
    }

    .btn-outline-primary:hover {
      background: #3B82F6;
      color: #fff;
    }

    .btn-outline-danger {
      color: #EF4444;
      border-color: #EF4444;
    }

    .btn-outline-danger:hover {
      background: #EF4444;
      color: #fff;
    }

    /* 分頁優化 */
    .pagination {
      gap: 5px;
    }

    .page-link {
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 8px;
      color: #9A3412;
      transition: all 0.3s ease;
    }

    .page-link:hover {
      background: rgba(154, 52, 18, 0.1);
      color: #9A3412;
      transform: translateY(-2px);
    }

    .page-item.active .page-link {
      background: linear-gradient(135deg, #9A3412, #EA580C);
      color: #fff;
      box-shadow: 0 4px 12px rgba(154, 52, 18, 0.2);
    }

    /* 統計資訊優化 */
    .stats-info {
      background: linear-gradient(135deg, #F5E3C3, #FFF5E7);
      padding: 1rem 1.5rem;
      border-radius: 12px;
      color: #9A3412;
      font-weight: 500;
      box-shadow: 0 4px 15px rgba(154, 52, 18, 0.05);
      margin-bottom: 1.5rem;
    }

    /* 響應式優化 */
    @media (max-width: 768px) {
      .container-fluid {
        padding: 1rem;
      }

      .table td, .table th {
        padding: 0.75rem;
      }

      .product-img {
        width: 60px;
        height: 60px;
      }

      .action-buttons .btn-icon {
        width: 30px;
        height: 30px;
      }
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
                  <img src="../logo-img/head-icon.png" alt="" class="head-icon mx-1 my-auto">
                  <!-- <i class="bi bi-shop-window fs-2 mt-1 mx-2"></i> -->
                  <!-- <a href="../index.php">首頁</a> / 商品管理 -->
                </div>
                <h1 class="page-title ms-2">商品列表</h1>
              </div>

            <!-- 篩選器 -->
            <div class="filter-section">
              <form class="row g-3" method="GET">
                <div class="col-md-2">
                  <!-- <label class="filter-label" for="category">商品分類</label> -->
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
                  <!-- <label class="filter-label" for="subcategory">子分類</label> -->
                  <select class="form-select" name="subcategory" id="subcategory">
                    <option value="">所有子分類</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <!-- <label class="filter-label" for="sort">排序方式</label> -->
                  <select class="form-select" name="sort" id="sort">
                    <option value="newest" <?= $sort === "newest" ? "selected" : "" ?>>最新上架</option>
                    <option value="oldest" <?= $sort === "oldest" ? "selected" : "" ?>>最舊上架</option>
                    <option value="price_high" <?= $sort === "price_high" ? "selected" : "" ?>>價格高到低</option>
                    <option value="price_low" <?= $sort === "price_low" ? "selected" : "" ?>>價格低到高</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <!-- <label class="filter-label" for="per_page">每頁顯示</label> -->
                  <select class="form-select" name="per_page" id="per_page">
                    <option value="10" <?= $per_page === 10 ? "selected" : "" ?>>每頁 10 筆</option>
                    <option value="20" <?= $per_page === 20 ? "selected" : "" ?>>每頁 20 筆</option>
                    <option value="50" <?= $per_page === 50 ? "selected" : "" ?>>每頁 50 筆</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <!-- <label class="filter-label" for="search">搜尋商品</label> -->
                  <input class="form-control" type="search" name="search" id="search"
                    placeholder="輸入商品名稱或描述..."
                    value="<?= isset($_GET["search"]) ? htmlspecialchars($_GET["search"]) : "" ?>">
                </div>
                <div class="col-md-1">
                  <button class="btn w-100 search-btn-effect mt-3" type="submit" id="searchBtn">
                    <i class="bi bi-search"></i>
                  </button>
                </div>
              </form>
            </div>

            <div class="d-flex justify-content-between mb-3">
              <div class="stats-info">
                <i class="bi bi-file-text me-2"></i>
                共 <?= $totalRows ?> 筆商品，目前顯示第 <?= $page ?> 頁，每頁 <?= $per_page ?> 筆
              </div>
              <div>
                <a href="product-create.php" class="btn btn-primary">
                  <i class="bi bi-plus-lg me-1"></i> 新增商品
                </a>
                <a href="product-trash.php" class="btn btn-outline-secondary ms-2">
                  <i class="bi bi-trash me-1"></i> 回收桶
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
      $(".delete-btn").on('click', function() {
        let id = $(this).data("id");
        console.log("Delete button clicked, ID:", id);
        
        Swal.fire({
          title: '確定要刪除嗎？',
          text: "此操作將會將商品移至回收桶",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#9A3412',
          cancelButtonColor: '#d33',
          confirmButtonText: '刪除',
          cancelButtonText: '取消'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: "product-delete.php",
              method: "POST",
              data: {
                id: id
              },
              dataType: 'json',
              success: function(response) {
                console.log("Response:", response);
                if (response.success) {
                  Swal.fire({
                    title: '刪除成功！',
                    text: '商品已移至回收桶',
                    icon: 'success',
                    confirmButtonColor: '#9A3412'
                  }).then((result) => {
                    location.reload();
                  });
                } else {
                  Swal.fire({
                    title: '刪除失敗',
                    text: response.message || '刪除過程發生錯誤',
                    icon: 'error',
                    confirmButtonColor: '#9A3412'
                  });
                }
              },
              error: function(xhr, status, error) {
                console.log("Error:", error);
                Swal.fire({
                  title: '系統錯誤',
                  text: '刪除過程發生錯誤',
                  icon: 'error',
                  confirmButtonColor: '#9A3412'
                });
              }
            });
          }
        });
      });

      // 表格行淡入效果
      $(".table tbody tr").each(function(index) {
        $(this).css({
          'animation-delay': (index * 0.1) + 's'
        }).addClass('fade-in');
      });

      // 搜尋按鈕點擊效果 - 移除彩帶，改為簡單的漣漪效果
      $("#searchBtn").click(function(e) {
        // 移除原有的彩帶效果代碼
        $(this).addClass('clicked');
        setTimeout(() => {
          $(this).removeClass('clicked');
        }, 200);
      });

      // 滑鼠移動特效
      let timeout;
      $(document).mousemove(function(e) {
        clearTimeout(timeout);
        timeout = setTimeout(function() {
          let sparkle = $("<div>", {
            css: {
              position: "fixed",
              width: "8px",
              height: "8px",
              background: "rgba(245, 227, 195, 0.8)",
              borderRadius: "50%",
              boxShadow: "0 0 10px rgba(154, 52, 18, 0.5)",
              pointerEvents: "none",
              left: e.clientX + "px",
              top: e.clientY + "px",
              zIndex: 9999
            }
          }).appendTo("body");

          sparkle.animate({
            width: "0px",
            height: "0px",
            opacity: 0
          }, 500, function() {
            sparkle.remove();
          });
        }, 50);
      });

      // 商品圖片載入動畫
      $(".product-img").each(function() {
        $(this).on('load', function() {
          $(this).addClass('fade-in');
        });
      });
    });
  </script>
  
  <!-- 噴射亮片 -->
  <!-- <script>
    document.getElementById("confettiBtn").addEventListener("click", () => {
      confetti({
        particleCount: 100,
        spread: 70,
        origin: { y: 0.6 }
      });
    });
  </script> -->
  <!-- 滑鼠光標特效 -->
  <!-- <script>
    document.addEventListener("mousemove", (e) => {
      let sparkle = document.createElement("div");
      sparkle.style.position = "absolute";
      sparkle.style.width = "5px";
      sparkle.style.height = "5px";
      sparkle.style.background = "gold";
      sparkle.style.borderRadius = "50%";
      sparkle.style.boxShadow = "0 0 5px gold";
      sparkle.style.left = `${e.pageX}px`;
      sparkle.style.top = `${e.pageY}px`;
      document.body.appendChild(sparkle);

      setTimeout(() => sparkle.remove(), 300);
    });
  </script> -->

</body>

</html>