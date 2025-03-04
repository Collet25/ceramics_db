<?php
require_once("../ceramics_db_connect.php");

$sqlAll = "SELECT * FROM users WHERE valid=1";
$resultAll = $conn->query($sqlAll);
$userCount = $resultAll->num_rows;

if (isset($_GET["q"])) {
    $q = $_GET["q"];
    if ($q == "") {
        header("Location: frozenUsers.php?p=1");
        exit;
    }
    $sql = "SELECT * FROM users WHERE valid=1 AND (name LIKE '%$q%' OR account LIKE '%$q%' OR email LIKE '%$q%' OR frozen LIKE '%$q%')";
} else if (isset($_GET["p"]) && isset($_GET["order"])) {

    $p = $_GET["p"];
    // $p = isset($_GET["p"]) && is_numeric($_GET["p"]) ? $_GET["p"] : 1;

    $order = $_GET["order"];

    $perPage = 10;
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
    $sql = "SELECT * FROM users WHERE valid = 1 $orderClause LIMIT $startItem, $perPage";
} else {
    header("location: frozenUsers.php?p=1&order=1");
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
    <title>停權會員</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="../logo-img/head-icon.png">
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
    </style>

</head>

<body class="g-sidenav-show">
    <!-- aside -->
    <?php include("../aside.php") ?>

    <main class="main-content position-relative max-height-vh-100 h-100  ">
        <!-- Navbar -->
        <?php include("../navbar.php") ?>

        <!-- frozenUsers -->
        <div class="container-fluid py-2">

            <div class="row">
                <div class="col-12">
                    <div class="d-flex align-items-center mx-4 p-2">
                        <div><i class="fa-solid fa-user-group fa-2x me-2"></i></div>

                        <div>
                            <h2>停權會員列表</h2>
                        </div>
                    </div>
                    <div class="mb-4 p-3">

                        <div class="container-fluid">
                            <div class="py-2 row d-flex justify-content-between align-items-center">
                                <div class="col-md-6">
                                    <a class="btn btn-primary" href="frozenUsers.php"><i class="fa-solid fa-circle-arrow-left me-2"></i>返回停權列表</a>
                                </div>

                                <div class="col-md-3">
                                    <form action="" method="get">
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
                                            <button class="btn btn-primary m-0"><i class="fa-solid fa-magnifying-glass" type="submit"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>



                            <div class="py-2">
                                <div class="row justify-content-center align-items-center">

                                    <!-- <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-auto d-flex align-items-end">
                                                <?php if (isset($_GET["p"])): ?>
                                                    <nav aria-label="Page navigation example">
                                                        <ul class="pagination me-0">
                                                            <li class="page-item">
                                                                <a class="page-link" href="frozenUsers.php?p=1" aria-label="Previous">
                                                                    <span aria-hidden="true"><i class="fa-solid fa-caret-left"></i></span>
                                                                </a>
                                                            </li>

                                                            <?php for ($i = 1; $i <= $totalPage; $i++): ?>
                                                                <?php $active = ($i == $_GET["p"]) ?
                                                                    "active" : "" ?>
                                                                <li class="page-item <?= $active ?>"><a class="page-link" href="frozenUsers.php?p=<?= $i ?>&order=<?= $order ?>"><?= $i ?></a></li>
                                                            <?php endfor; ?>

                                                            <li class="page-item">
                                                                <a class="page-link" href="frozenUsers.php?p=<?= $totalPage ?>&order=<?= $order ?>" aria-label="Next">
                                                                    <span aria-hidden="true"><i class="fa-solid fa-caret-right"></i></span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </nav>
                                                <?php endif ?>
                                            </div>
                                            <div class="col-auto py-3 d-flex align-items-end">
                                                共 <?= $userCount ?> 名被停權
                                            </div>
                                        </div>
                                    </div> -->

                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-auto d-flex align-items-end">
                                                <?php if (isset($_GET["p"])): ?>
                                                    <nav aria-label="Page navigation example">
                                                        <ul class="pagination me-0">
                                                            <li class="page-item">
                                                                <a class="page-link" href="frozenUsers.php?p=1" aria-label="Previous">
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
                                                                <li class="page-item <?= $active ?>"><a class="page-link" href="frozenUsers.php?p=<?= $i ?>&order=<?= $order ?>"><?= $i ?></a></li>
                                                            <?php endfor; ?>

                                                            <li class="page-item">
                                                                <a class="page-link" href="frozenUsers.php?p=<?= $totalPage ?>&order=<?= $order ?>" aria-label="Next">
                                                                    <span aria-hidden="true"><i class="fa-solid fa-caret-right"></i></span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </nav>
                                                <?php endif ?>
                                            </div>
                                            <div class="col-auto py-3 d-flex align-items-end">
                                                共 <?= $userCount ?> 名停權會員
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3 d-flex justify-content-end">
                                        <div class="btn-group">
                                            <div class="dropdown me-2">
                                                <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span class="fw-bold"><i class="fa-solid fa-filter me-2"></i>排序</span>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li><a class="dropdown-item" <?php if ($order == 1) echo "active" ?>" href="frozenUsers.php?p=<?= $p ?>&order=1">ID 由小到大</a></li>
                                                    <li><a class="dropdown-item" <?php if ($order == 2) echo "active" ?>" href="frozenUsers.php?p=<?= $p ?>&order=2">ID 由大到小</a></li>
                                                    <li><a class="dropdown-item" <?php if ($order == 3) echo "active" ?>" href="frozenUsers.php?p=<?= $p ?>&order=3">帳號 A → Z</a></li>
                                                    <li><a class="dropdown-item" <?php if ($order == 4) echo "active" ?>" href="frozenUsers.php?p=<?= $p ?>&order=4">帳號 Z → A</a></li>
                                                </ul>
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
                                                <th class="text-center">加入日期</th>
                                                <th class="text-center">停權原因</th>
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
                                                    <td class="text-center"><?= $row["created_at"] ?></td>
                                                    <td class="text-center"><?= $row["frozen"] ?></td>
                                                    <td class="text-center">
                                                        <a class="btn btn-secondary text-center" href="frozenUser.php?id=<?= $row["id"] ?>"><i class="fa-solid fa-eye fa-fw  text-center"></i></a>

                                                        <a class="btn btn-primary" href="frozenUser-edit.php?id=<?= $row["id"] ?>"><i class="fa-solid fa-pen-to-square fa-fw"></i></a>
                                                    </td>
                                                </tr>
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