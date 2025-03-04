<?php
require_once("../ceramics_db_connect.php");


$sqlAll = "SELECT * FROM users WHERE valid=0";
$resultAll = $conn->query($sqlAll);
$userCount = $resultAll->num_rows;

if (isset($_GET["q"])) {
    $q = $_GET["q"];
    if ($q == "") {
        header("Location: users.php?p=1");
        exit;
    }
    $sql = "SELECT * FROM users WHERE name LIKE '%$q%' OR account LIKE '%$q%' OR email LIKE '%$q%'";
} else if (isset($_GET["p"]) && isset($_GET["order"])) {

    $p = $_GET["p"];
    // $p = isset($_GET["p"]) && is_numeric($_GET["p"]) ? $_GET["p"] : 1;

    $order = $_GET["order"];

    $perPage = 10;
    // $perPage = isset($_GET['perPage']) && in_array($_GET['perPage'], [10, 20, 30]) ? $_GET['perPage'] : 10;
    $startItem = ($p - 1) * $perPage;
    $totalPage = ceil($userCount / $perPage);

    // if($order == 1){
    //     $sql = "SELECT * FROM users WHERE valid = 0 ORDER BY id ASC LIMIT $startItem, $perPage";
    // }elseif($order == 2){
    //     $sql = "SELECT * FROM users WHERE valid = 0 ORDER BY id DESC LIMIT $startItem, $perPage";
    // }
    $orderClause = "";
    switch ($order) {
        case 1:
            $orderClause = "ORDER BY id ASC";
            break;
        case 2:
            $orderClause = "ORDER BY id DESC";
            break;
        case 3:
            $orderClause = "ORDER BY account ASC";
            break;
        case 4:
            $orderClause = "ORDER BY account DESC";
            break;
    }
    $sql = "SELECT * FROM users WHERE valid = 0 $orderClause LIMIT $startItem, $perPage";
} else {
    header("location: users.php?p=1&order=1");
}


$result = $conn->query($sql);
$rows = $result->fetch_all(MYSQLI_ASSOC);
if (isset($_GET["q"])) {
    $userCount = $result->num_rows;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/png" href="../logo-img/head-icon.png">
    <title>會員列表</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <?php include("../css.php") ?>


    <style>
        .custom-row th {
            background-color: #9A3412 !important;
            color: white !important;
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
        }

        .modal-body {
            max-height: 100px;
            /* 設定最大高度 */
            overflow-y: auto;
            /* 允許滾動 */
        }

        .page-header {
            border: none; /* 先清除所有邊框 */
            border-bottom: 2px solid #9A3412; /* 只加底部邊框 */

        }
    </style>

</head>

<body class="g-sidenav-show">
    <!-- aside -->
    <?php include("../aside.php") ?>

    <main class="main-content position-relative max-height-vh-100 h-100  ">
        <!-- Navbar -->
        <?php include("../navbar.php") ?>

        <!-- users -->
        <div class="container-fluid py-2">

            <div class="row">
                <div class="col-12">

                    <div class="d-flex align-items-center mx-4 p-3 page-header">
                        <div>
                            <i class="fa-solid fa-user-group fa-2x me-3"></i>
                        </div>
                        <div>
                            <h2 style="color: #9A3412;">會員列表</h2>
                        </div>
                    </div>
                    <div class="mb-4 p-3">


                        <div class="container-fluid">
                            <div class="py-2 row d-flex justify-content-between align-items-center">
                                <div class="col-md-6">
                                    <a class="btn btn-primary" href="users.php"><i class="fa-solid fa-circle-arrow-left me-2"></i>返回會員列表</a>
                                </div>

                                <div class="col-md-3">
                                    <form method="get">
                                        <div class="input-group">
                                            <input type="search"
                                                class="form-control" name="q" placeholder="輸入關鍵字"
                                                <?php
                                                $q = $_GET["q"] ?? "";
                                                // $q=(isset($_GET["q"]))?
                                                // $_GET["q"] : "";
                                                // 
                                                ?>
                                                value="<?= $q ?>">
                                            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>



                            <div class="py-2">
                                <div class="row justify-content-center align-items-center">

                                    <!-- <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-auto d-flex align-items-end">
                                                <?php if (isset($_GET["p"])): ?>
                                                    <nav aria-label="Page navigation example">
                                                        <ul class="pagination me-0">
                                                            <li class="page-item">
                                                                <a class="page-link" href="users.php?p=1" aria-label="Previous">
                                                                    <span aria-hidden="true"><i class="fa-solid fa-caret-left"></i></span>
                                                                </a>
                                                            </li>

                                                            <?php for ($i = 1; $i <= $totalPage; $i++): ?>
                                                                <?php $active = ($i == $_GET["p"]) ?
                                                                    "active" : "" ?>
                                                                <li class="page-item <?= $active ?>"><a class="page-link" href="users.php?p=<?= $i ?>&order=<?= $order ?>"><?= $i ?></a></li>
                                                            <?php endfor; ?>

                                                            <li class="page-item">
                                                                <a class="page-link" href="users.php?p=<?= $totalPage ?>&order=<?= $order ?>" aria-label="Next">
                                                                    <span aria-hidden="true"><i class="fa-solid fa-caret-right"></i></span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </nav>
                                                <?php endif ?>
                                            </div>
                                            <div class="col-auto py-3 d-flex align-items-end">
                                                共 <?= $userCount ?> 名會員
                                            </div>
                                        </div>
                                    </div> -->

                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-auto d-flex align-items-end">
                                                <?php if (isset($_GET["p"])): ?>
                                                    <nav aria-label="Page navigation example">
                                                        <ul class="pagination me-0">
                                                            <li class="page-item">
                                                                <a class="page-link" href="users.php?p=1" aria-label="Previous">
                                                                    <span aria-hidden="true"><i class="fa-solid fa-caret-left"></i></span>
                                                                </a>
                                                            </li>

                                                            <?php
                                                            // 獲取當前頁面和總頁數
                                                            $currentPage = $_GET["p"];
                                                            $totalPage = $totalPage;  // 假設 $totalPage 已經定義

                                                            // 計算顯示頁面範圍（最多顯示 5 頁）
                                                            $startPage = max(1, $currentPage - 2);  // 顯示當前頁之前的頁面
                                                            $endPage = min($totalPage, $currentPage + 2);  // 顯示當前頁之後的頁面

                                                            // 如果顯示的頁面少於 5 頁，調整顯示範圍
                                                            if ($currentPage <= 3) {
                                                                $endPage = min(5, $totalPage);  // 如果在開始處，顯示更多的頁面
                                                            } elseif ($currentPage >= $totalPage - 2) {
                                                                $startPage = max($totalPage - 4, 1);  // 如果在結尾處，顯示更多前面的頁面
                                                            }

                                                            // 顯示頁碼
                                                            for ($i = $startPage; $i <= $endPage; $i++):
                                                                $active = ($i == $currentPage) ? "active" : "";
                                                            ?>
                                                                <li class="page-item <?= $active ?>"><a class="page-link" href="users.php?p=<?= $i ?>&order=<?= $order ?>"><?= $i ?></a></li>
                                                            <?php endfor; ?>

                                                            <li class="page-item">
                                                                <a class="page-link" href="users.php?p=<?= $totalPage ?>&order=<?= $order ?>" aria-label="Next">
                                                                    <span aria-hidden="true"><i class="fa-solid fa-caret-right"></i></span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </nav>
                                                <?php endif ?>
                                            </div>
                                            <div class="col-auto py-3 d-flex align-items-end">
                                                共 <?= $userCount ?> 名會員
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 d-flex justify-content-end">
                                        <div class="btn-group">
                                            <div class="dropdown me-2">
                                                <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span class="fw-bold"><i class="fa-solid fa-filter me-2"></i>排序</span>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li><a class="dropdown-item" <?php if ($order == 1) echo "active" ?>" href="users.php?p=<?= $p ?>&order=1">ID 由小到大</a></li>
                                                    <li><a class="dropdown-item" <?php if ($order == 2) echo "active" ?>" href="users.php?p=<?= $p ?>&order=2">ID 由大到小</a></li>
                                                    <li><a class="dropdown-item" <?php if ($order == 3) echo "active" ?>" href="users.php?p=<?= $p ?>&order=3">帳號 A → Z</a></li>
                                                    <li><a class="dropdown-item" <?php if ($order == 4) echo "active" ?>" href="users.php?p=<?= $p ?>&order=4">帳號 Z → A</a></li>
                                                </ul>

                                                <!-- <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="fw-bold">每頁 <?= $perPage ?> 筆</span>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <li><a class="dropdown-item per-page-option" href="?p=<?= $p ?>&order=<?= $order ?>&perPage=10">每頁 10 筆</a></li>
                                                <li><a class="dropdown-item per-page-option" href="?p=<?= $p ?>&order=<?= $order ?>&perPage=20">每頁 20 筆</a></li>
                                                <li><a class="dropdown-item per-page-option" href="?p=<?= $p ?>&order=<?= $order ?>&perPage=30">每頁 30 筆</a></li>
                                            </ul> -->
                                            </div>
                                            <div class="">
                                                <a class="btn btn-primary" href="sign-up.php"><i class="fa-solid fa-user-plus me-2"></i>新增會員</a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <?php if ($userCount > 0): ?>
                                <div class="table-responsive">
                                    <table class="table border-1 table-striped align-middle ">
                                        <thead class="custom-row">
                                            <tr class="">
                                                <th class="text-center">ID</th>
                                                <th class="text-center">帳號</th>
                                                <th class="text-center">姓名</th>
                                                <th class="text-center">信箱</th>
                                                <th class="text-center">性別</th>
                                                <th class="text-center">加入日期</th>
                                                <th class="text-center">操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($rows as $row): ?>
                                                <tr>
                                                    <td class="text-center"><?= $row["id"] ?></td>
                                                    <td class="text-center"><?= $row["account"] ?></td>
                                                    <td class="text-center"><?= $row["name"] ?></td>
                                                    <td class="text-center"><?= $row["email"] ?></td>
                                                    <td class="text-center"><?= $row["gender"] ?></td>
                                                    <td class="text-center"><?= $row["created_at"] ?></td>
                                                    <td class="text-center">
                                                        <a class="btn btn-secondary text-center m-0 py-2" href="user.php?id=<?= $row["id"] ?>">
                                                            <i class="fa-solid fa-eye fa-fw text-center"></i>
                                                        </a>

                                                        <a class="btn btn-primary m-0 py-2" href="user-edit.php?id=<?= $row["id"] ?>">
                                                            <i class="fa-solid fa-pen-to-square fa-fw"></i>
                                                        </a>

                                                        <!-- 將 data-bs-target 改為唯一的 modal id -->
                                                        <a class="btn btn-danger m-0 py-2" data-bs-toggle="modal" data-bs-target="#infoModal<?= $row["id"] ?>">
                                                            <i class="fa-solid fa-trash fa-fw"></i>
                                                        </a>
                                                    </td>
                                                </tr>

                                                <!-- 將 modal id 改為唯一 -->
                                                <div class="modal fade" id="infoModal<?= $row["id"] ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?= $row["id"] ?>" aria-hidden="true">
                                                    <div class="modal-dialog modal-sm">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="exampleModalLabel<?= $row["id"] ?>">系統資訊</h1>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                確定凍結該帳號?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <a role="button" class="btn btn-danger" href="doFrozenUser.php?id=<?= $row["id"] ?>">確定</a>
                                                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">取消</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>

        <!-- 頁尾 -->
        <?php include("../footer.php"); ?>
    </main>

    <?php include("../js.php") ?>

</body>

</html>