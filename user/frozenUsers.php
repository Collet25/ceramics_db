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
    $sql = "SELECT * FROM users WHERE name LIKE '%$q%' OR account LIKE '%$q%' OR email LIKE '%$q%' OR frozen LIKE '%$q%'";
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
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php include("../css.php") ?>

    <style>
        
        .custom-row th {
  background-color: rgb(16, 108, 105) !important;
  color: white !important;
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
        <div class="container-fluid py-4">

            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card border-0 mb-4 mx-4 p-3">

                        <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="" aria-hidden="true">
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="">系統資訊</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        確定恢復該會員資格?
                                    </div>
                                    <div class="modal-footer">
                                        <a role="button" class="btn btn-danger" href="recoverUser.php?id=<?= $row["id"] ?>">確定</a>
                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">取消</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="container">

                            <div class="py-3 row d-flex justify-content-between align-items-center">
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



                            <div class="py-3 justify-content-between align-items-center">
                                <div class="row justify-content-center align-items-center">

                                    <div class="col-12 ">
                                        <div class="row d-flex justify-content-start align-items-center">
                                            <div class="col-auto">
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
                                            <div class="col-auto">
                                                共 <?= $userCount ?> 名被停權
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