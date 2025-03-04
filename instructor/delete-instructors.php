<?php
require_once("../ceramics_db_connect.php");

$sqlAll = "SELECT * FROM instructor WHERE valid=0";
$result = $conn->query($sqlAll);
$rows = $result->fetch_all(MYSQLI_ASSOC);
$userCount = $result->num_rows;
// var_dump($rows);
if (isset($_GET["q"])) {
    $q = $_GET["q"];
    $sql = "SELECT * FROM instructor WHERE valid=0 AND (name LIKE '%$q%' OR phone LIKE '%$q%')";
    $result = $conn->query($sql);
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $userCount = $result->num_rows;
} else if (isset($_GET["P"])) {
    $P = $_GET["P"];
    $order = $_GET["order"];
    $orderClause = "";
    switch ($order) {
        case 1:
            $orderClause = "ORDER BY id ASC";
            break;
        case 2:
            $orderClause = "ORDER BY id DESC";
            break;
        case 3:
            $orderClause = "ORDER BY deleted_at ASC";
            break;
        case 4:
            $orderClause = "ORDER BY deleted_at DESC";
            break;
    }
    $perPage = 6;
    $startItem = ($P - 1) * $perPage;
    $totalPage = ceil($userCount / $perPage);

    $sql = "SELECT * FROM instructor WHERE valid=0 $orderClause LIMIT $startItem, $perPage";
    $result = $conn->query($sql);
    $rows = $result->fetch_all(MYSQLI_ASSOC);
} else {
    header("location:delete-instructors.php?P=1&order=1");
}
?>
<!doctype html>
<html lang="en">

<head>
    <title>刪除名單</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../logo-img/head-icon.png">
    <?php include("../css.php") ?>
</head>

<body>
    <?php include("../aside.php") ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <?php include("../navbar.php"); ?>
        <div class="container-fluid py-4 px-5 ">
            <!-- 主要內容 -->
            <div class="row">
                <div class="col-12">
                    <div class="card border shadow-xs mb-4">
                        <div class="card-header border-bottom pb-0">
                            <div class="d-sm-flex align-items-center">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">刪除名單</h6>
                                    <p class="text-sm">查看被刪除的老師</p>
                                </div>
                                <div class="ms-auto d-flex">
                                    <a href="./instructors.php" class="btn btn-primary px-3"><i class="fa-solid fa-house-user pe-2"></i>回成員名單</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 py-0">
                            <div class="border-bottom py-3 px-3 d-sm-flex align-items-center justify-content-between">
                                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                    <a href="./delete-instructors.php?=$P?>&order=1" class="btn btn-light text-secondary <?php if ($order == 1) echo "active" ?>">ID<i class="fa-solid fa-arrow-down-1-9"></i></a>
                                    <a href="./delete-instructors.php?P=<?= $P ?>&order=2" class="btn btn-light text-secondary <?php if ($order == 2) echo "active" ?>">ID<i class="fa-solid fa-arrow-down-9-1"></i></a>
                                    <a href="./delete-instructors.php?P=<?= $P ?>&order=3" class="btn btn-light text-secondary <?php if ($order == 3) echo "active" ?>">刪除時間舊到新<i class="fa-solid fa-down-long"></i></a>
                                    <a href="./delete-instructors.php?P=<?= $P ?>&order=4" class="btn btn-light text-secondary <?php if ($order == 4) echo "active" ?>">刪除時間新到舊<i class="fa-solid fa-down-long"></i></a>
                                </div>
                                <form action="" method="get">
                                    <div class="input-group w-100 ms-auto">
                                        <input type="search" class="form-control" name="q"
                                            <?php
                                            $q = $_GET["q"] ?? "";
                                            ?>
                                            value="<?= $q ?>">
                                        <button class="btn btn-primary"><i class="fa-solid fa-magnifying-glass fa-fw" type="submit"></i></button>
                                    </div>
                                </form>
                            </div>
                            <div class="m-3">
                                <?php if (isset($_GET["q"])): ?>
                                    <a href="./delete-instructors.php" class="btn btn-primary"><i class="fa-solid fa-arrow-left pe-2"></i>回刪除名單</a>
                                <?php endif; ?>
                                <div class="mt-3 px-3"> 共<?= $userCount ?>位使用者</div>
                            </div>
                            <div class="table-responsive p-0">
                                <?php if ($userCount > 0): ?>
                                    <table class="table align-items-center mb-0">
                                        <thead class="bg-gray-200">
                                            <tr>
                                                <th class="text-center">ID</th>
                                                <th class="text-start">姓名/email</th>
                                                <th class="text-start">性別</th>
                                                <th class="text-start">電話</th>
                                                <th class="text-start">簡介</th>
                                                <th class="text-start">加入時間</th>
                                                <th class="text-start">刪除時間</th>
                                                <th class="text-start">復原</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($rows as $row): ?>
                                                <tr>
                                                    <td>
                                                        <p class="text-sm text-dark font-weight-semibold mb-0 text-center"><?= $row["id"] ?></p>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex px-2 py-1">
                                                            <div class="d-flex align-items-center">
                                                                <img src="<?= $row["img"] ?>" class="avatar avatar-sm rounded-circle me-2"
                                                                    alt="user1">
                                                            </div>
                                                            <div class="d-flex flex-column justify-content-center ms-1">
                                                                <h6 class="mb-0 text-sm font-weight-semibold text-start"><?= $row["name"] ?></h6>
                                                                <p class="text-sm text-secondary mb-0"><?= $row["email"] ?></p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p class="text-sm text-dark font-weight-semibold mb-0 text-start"><?= $row["gender"] ?></p>
                                                    </td>
                                                    <td>
                                                        <p class="text-sm text-dark font-weight-semibold mb-0 text-start"><?= $row["phone"] ?></p>
                                                    </td>
                                                    <td>
                                                        <p class="text-sm text-dark font-weight-semibold mb-0 text-start"><?= $row["bio"] ?></p>
                                                    </td>
                                                    <td>
                                                        <p class="text-sm text-secondary mb-0 text-start"><?= $row["created_at"] ?></p>
                                                    </td>
                                                    <td>
                                                        <p class="text-sm text-secondary mb-0 text-start"><?= $row["deleted_at"] ?></p>
                                                    </td>
                                                    <td class="align-middle text-start">
                                                        <a href="../instructor/delete-instructor.php?id=<?= $row["id"] ?>" class="btn btn-primary"><i class="fa-solid fa-eye"></i></a>
                                                        <a href="../instructor/instructorRestore.php?id=<?= $row["id"] ?>" class="px-3 restore-btn btn btn-danger"><i class="fa-solid fa-rotate-right"></i></a>
                                                        </a>
                                                    </td>
                                                </tr>
                                        </tbody>
                                    <?php endforeach; ?>
                                    </table>
                                <?php endif; ?>
                            </div>
                            <?php if (isset($_GET["P"])): ?>
                                <div class="border-top py-3 px-3 d-flex align-items-center">
                                    <nav aria-label="..." class="m-auto">
                                        <ul class="pagination mb-0">
                                            <?php for ($i = 1; $i <= $totalPage; $i++): ?>
                                                <?php
                                                $active = ($i == $_GET["P"]) ?
                                                    "active" : "";
                                                ?>
                                                <li class="page-item <?= $active ?>"><a class="page-link border-0 font-weight-bold" href="./delete-instructors.php?P=<?= $i ?>&order=<?= $order ?>"><?= $i ?></a></li>
                                            <?php endfor; ?>
                                        </ul>
                                    </nav>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include("../footer.php") ?>
    </main>
    <?php include("../js.php") ?>
</body>

</html>