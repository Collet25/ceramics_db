<?php
if (!isset($_GET["id"])) {
    header("location: users.php");
}
$id = $_GET["id"];

require_once("../ceramics_db_connect.php");
$sql = "SELECT * FROM users WHERE id = $id AND valid=1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$userCount = $result->num_rows;
// var_dump($row);

?>

<!doctype html>
<html lang="zh-TW">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <?php include("../css.php") ?>
</head>

<body class="g-sidenav-show">
    <?php include("../aside.php") ?>

    <main class="main-content position-relative max-height-vh-100 h-100  ">
        <!-- Navbar -->
        <?php include("../navbar.php") ?>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
           
            
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card border-0 mb-4 mx-4 p-3">


                        <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="" aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="">系統資訊</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        確定恢復該會員資格
                                    </div>
                                    <div class="modal-footer">
                                        <a role="button" class="btn btn-danger" href="recoverUser.php?id=<?= $row["id"] ?>">確定</a>
                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">取消</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            <div class="row d-flex justify-content-center py-3">
                                <div class="col-md-8 col-sm-6">
                                    <?php if ($userCount > 0): ?>
                                        <form action="doUpdatefrozenUser.php" method="post">
                                            <input type="hidden" name="id" value="<?= $row["id"] ?>">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>ID</th>
                                                    <td><?= $row["id"] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>名字</th>
                                                    <td><?= $row["name"] ?></td>         
                                                </tr>
                                                <tr>
                                                    <th>帳號</th>
                                                    <td><?= $row["account"] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>性別</th>
                                                    <td><?= $row["gender"] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>電話</th>
                                                    <td><?= $row["phone"] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>信箱</th>
                                                    <td><?= $row["email"] ?></td>

                                                </tr>
                                                <tr>
                                                    <th>生日</th>
                                                    <td><?= $row["birth"] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>加入時間</th>
                                                    <td><?= $row["created_at"] ?></td>
                                                </tr>
                                                <tr>
                                                    <th style="color:red;">停權原因</th>
                                                    <td>
                                                        <input type="text" class="form-control" style="color:red;"name="frozen" value="<?= $row["frozen"] ?>">
                                                    </td>
                                                </tr>
                                            </table>
                                            <div class="d-flex justify-content-center">
                                                <div class="py-2 me-2">
                                                    <a href="frozenUsers.php" class="btn btn-primary"><i class="me-2 fa-solid fa-arrow-left fa-fw"></i>返回列表</a>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <div class="py-2 me-2">
                                                        <button class="btn btn-primary" type="submit"><i class="me-2 fa-solid fa-floppy-disk fa-fw"></i>儲存
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="py-2 text-end">
                                                <a class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#infoModal">帳號解凍
                                                </a>
                                            </div>
                                        </form>
                                    <?php else: ?>
                                        <h2>該會員帳號不存在</h2>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
        </div>
    </main>
    <?php include("../js.php") ?>
</body>

</html>