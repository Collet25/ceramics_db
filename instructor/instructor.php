<?php
require_once("../ceramics_db_connect.php");

if (!isset($_GET["id"])) {
    header("location: instructors.php");
}

$id = $_GET["id"];

$sql = "SELECT * FROM instructor WHERE id=$id AND valid=1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$instructorCount = $result->num_rows;

// var_dump($row);
?>
<!doctype html>
<html lang="en">

<head>
    <title>古瓷宮（CUCCI）</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Bootstrap CSS v5.2.1 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous" />
    <link rel="apple-touch-icon" sizes="76x76" href="../logo-img/head-icon.png">
    <link rel="icon" type="image/png" href="../logo-img/head-icon.png">
    <?php include("../css.php") ?>
</head>

<body class="g-sidenenav-show">
    <?php include("../aside.php") ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <?php
        $navbarTitle = "主頁";
        $navbarLink = "師資陣容";
        $navbarText = "老師資訊";
        include("../navbar.php"); ?>
        <div class="container-fluid py-4 px-5">
            <h2 class="text-center mb-4">老師資訊</h2>
            <div class="row">
                <div class="col-6 m-auto pt-3">
                    <?php if ($instructorCount > 0) : ?>
                        <table class="table table-bordered table-hover shadow-lg">
                            <tr>
                                <th>ID</th>
                                <td class="text-start"><?= $row["id"] ?></td>
                            </tr>
                            <tr>
                                <th>照片</th>
                                <td>
                                    <img src="<?= $row["img"] ?>" alt="老師照片"
                                        class="rounded-3 shadow-lg img-fluid"
                                        style="width: 300px; height: 300px;">
                                </td>
                            </tr>
                            <tr>
                                <th>姓名</th>
                                <td class="text-start"><?= $row["name"] ?></td>
                            </tr>
                            <tr>
                                <th>性別</th>
                                <td class="text-start"><?= $row["gender"] ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td class="text-start"><?= $row["email"] ?></td>
                            </tr>
                            <tr>
                                <th>手機</th>
                                <td class="text-start"><?= $row["phone"] ?></td>
                            </tr>
                            <tr>
                                <th>簡介</th>
                                <td class="text-start"><?= $row["bio"] ?></td>
                            </tr>
                            <tr>
                                <th>創建時間</th>
                                <td class="text-start"><?= $row["created_at"] ?></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-center">
                                    <a href="./instructors.php" class="btn btn-primary me-2">回成員名單</a>
                                    <a href="./instructor-edit.php?id=<?= $row["id"] ?>" class="btn btn-secondary">編輯</a>
                                </td>
                            </tr>
                        </table>
                    <?php else: ?>
                        <h2>使用者不存在</h2>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php include("../footer.php") ?>
    </main>
    <?php include("../js.php") ?>
</body>

</html>