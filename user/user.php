<?php
if (!isset($_GET["id"])) {
    header("location: users.php");
}
$id = $_GET["id"];

require_once("../ceramics_db_connect.php");
$sql = "SELECT * FROM users WHERE id = $id AND valid=0";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$userCount = $result->num_rows;
// var_dump($row);

?>

<!doctype html>
<html lang="zh-TW">

<head>
    <title>會員資料</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="icon" type="image/png" href="../logo-img/head-icon.png">
    <?php include("../css.php") ?>
    <style>
        .user-table {
            border-radius: 0;
            /* border:none; */
            box-shadow: none;
        }

        .image-border {
            border: 1px solid rgb(231, 205, 205);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
    </style>
</head>

<body class="g-sidenav-show">
    <!-- aside -->
    <?php include("../aside.php") ?>

    <main class="main-content position-relative max-height-vh-100 h-100  ">
        <!-- Navbar -->
        <?php include("../navbar.php") ?>

        <!-- user -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 col-12">

                    <div class="d-flex justify-content-center align-items-center mb-4 mx-4 p-2">
                        <div><i class="fa-solid fa-user-group fa-2x me-2"></i></div>
                        <div>
                            <h2>會員資料</h2>
                        </div>
                    </div>

                    <div class="card mb-4 mx-4 p-5">

                        <div class="container">

                            <div class="mb-2 d-flex justify-content-start align-items-center">
                                <a href="users.php" class="btn btn-primary d-flex align-items-center me-3 "><i class="fa-solid fa-arrow-left fa-fw"></i></a>
                                <div class="fs-3 d-flex align-items-center">
                                    <?= $row["name"] ?>的個人資訊
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">

                                <div class="col-6 mt-4">
                                    <!-- 頭像 -->
                                    <div class="d-flex justify-content-start align-items-start">
                                        <div class="col-10">
                                            <div class="ratio ratio-1x1 image-border">
                                                <img class="object-fit-cover" src="../user-upload/<?= $row["image"] ?>" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <?php if ($userCount > 0): ?>
                                        <table class="user-table table align-middle">
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
                                        </table>
                                    <?php else: ?>
                                        <h2>該會員帳號不存在</h2>
                                    <?php endif; ?>
                                    <div class="d-flex justify-content-end">

                                        <div class="py-2 text-end">
                                            <a class="btn btn-primary" href="user-edit.php?id=<?= $row["id"] ?>"><i class="me-2 fa-solid fa-pen-to-square fa-fw"></i>編輯</a>
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
        </div>

        <!-- 頁尾 -->
        <?php include("../footer.php"); ?>

    </main>

    <?php include("../js.php") ?>
</body>

</html>