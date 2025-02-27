<!--     Fonts and icons     -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Noto+Sans:300,400,500,600,700,800|PT+Mono:300,400,500,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/349ee9c857.js" crossorigin="anonymous"></script>
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/corporate-ui-dashboard.css?v=1.0.0" rel="stylesheet" />

  <!-- bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- 商品列表 -->
  <link href="../products/style_p.css" rel="stylesheet">

<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;500;700&display=swap" rel="stylesheet">

<!-- Custom CSS -->
<style>
:root {
    /* 主要配色 */
    --main-bg: #F8F1E3;      /* 主背景：米白色 */
    --sub-bg: #EAEAEA;       /* 次背景：淺灰色 */
    --main-text: #3F3F3F;    /* 主要文字：深灰色 */
    --sub-text: #6B7280;     /* 次要文字：淺灰色 */
    --primary: #7B2D12;      /* 主色：磚紅 */
    --secondary: #EA580C;    /* 輔助色：深橘 */
    
    /* 按鈕配色 */
    --btn-primary: #9A3412;  /* 新增/主要：磚紅 */
    --btn-danger: #DC2626;   /* 危險/刪除：紅色 */
    --btn-cancel: #D1D5DB;   /* 取消/返回：淺灰 */
    
    /* 側邊欄配色 */
    --sidebar-bg: #2D2D2D;   /* 背景：深灰色 */
    --sidebar-text: #D1D5DB; /* 未選中文字：淺灰 */
    --sidebar-active: #9A3412; /* 選中狀態：磚紅 */
    --sidebar-hover: #EA580C; /* Hover效果：深橘 */
}

body {
    font-family: 'Noto Sans TC', sans-serif;
    background-color: var(--main-bg);
    color: var(--main-text);
}

/* 通用按鈕樣式 */
.btn-primary {
    background-color: var(--btn-primary);
    border-color: var(--btn-primary);
}

.btn-primary:hover {
    background-color: var(--secondary);
    border-color: var(--secondary);
}

.btn-danger {
    background-color: var(--btn-danger);
    border-color: var(--btn-danger);
}

.btn-cancel {
    background-color: var(--btn-cancel);
    border-color: var(--btn-cancel);
    color: var(--main-text);
}

/* 卡片樣式 */
.card {
    background-color: #FFFFFF;
    border: none;
    border-radius: 1rem;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
}

.card-header {
    background-color: transparent;
    border-bottom: 1px solid var(--sub-bg);
    padding: 1.5rem;
}

/* 表格樣式 */
.table th {
    color: var(--sub-text);
    font-weight: 500;
    border-bottom-width: 1px;
}

.table td {
    color: var(--main-text);
    vertical-align: middle;
}

/* 表單元素樣式 */
.form-control, .form-select {
    border-radius: 0.5rem;
    border-color: var(--sub-bg);
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 0.25rem rgba(123, 45, 18, 0.25);
}
</style>