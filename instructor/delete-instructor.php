<?php
require_once("../ceramics_db_connect.php");

if (!isset($_GET["id"])) {
    header("location: instructors.php");
}

$id = $_GET["id"];
$sql = "SELECT instructor.*, artwork.artname, artwork.image AS artwork_image
FROM instructor
LEFT JOIN artwork ON instructor.id = artwork.instructor_id
WHERE instructor.id = $id AND instructor.valid = 0";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$instructorCount = $result->num_rows;

// var_dump($row);
?>
<!doctype html>
<html lang="en">

<head>
    <title>老師資料</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../logo-img/head-icon.png">
    <?php include("../css.php") ?>
</head>

<body class="g-sidenenav-show">
    <?php include("../aside.php") ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <?php include("../navbar.php"); ?>

        <div class="container py-4 px-5">
            <?php if ($instructorCount > 0) : ?>
                <!-- 老師個人資訊標題 -->
                <h2 class="text-center mb-4"><?= $row["name"] ?>的個人資訊</h2>
                <!-- 按鈕區域 -->
                <div class=" d-flex align-items-center mb-2">
                    <a href="./delete-instructors.php?id=<?= $row["id"] ?>" class="btn btn-primary"><i class="fa-solid fa-arrow-left"></i>返回停權名單</a>
                    <!-- <a href="./instructor-edit.php?id=<?= $row["id"] ?>" class="btn btn-secondary">編輯</a> -->
                </div>
                <!-- 上-->
                <div class="card mb-3">
                    <div class="row g-0 align-items-center">
                        <div class="col-md-4">
                            <img src="<?= $row["img"] ?>" alt="老師照片" class="img-fluid rounded-start shadow-lg" style="object-fit: cover; width:100%; height:100%;">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body w-75 mx-auto">
                                <dl class="row g-3">
                                    <dt class="col-sm-3">ID</dt>
                                    <dd class="col-sm-9"><?= $row["id"] ?></dd>
                                    <hr class="mt-2">
                                    <dt class="col-sm-3">姓名</dt>
                                    <dd class="col-sm-9"><?= $row["name"] ?></dd>
                                    <hr class="mt-2">
                                    <dt class="col-sm-3">性別</dt>
                                    <dd class="col-sm-9"><?= $row["gender"] ?></dd>
                                    <hr class="mt-2">
                                    <dt class="col-sm-3">Email</dt>
                                    <dd class="col-sm-9"><?= $row["email"] ?></dd>
                                    <hr class="mt-2">
                                    <dt class="col-sm-3">手機</dt>
                                    <dd class="col-sm-9"><?= $row["phone"] ?></dd>
                                    <hr class="mt-2">
                                    <dt class="col-sm-3">簡介</dt>
                                    <dd class="col-sm-9"><?= $row["bio"] ?></dd>
                                    <hr class="mt-2">
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 下-->
                <h2 class="text-center mt-5 mb-4">主要作品</h2>
                <div class="card mb-3 ">
                    <div class="row g-0 align-items-center">
                        <div class="col-md-4">
                            <img src="<?= $row["artwork_image"] ?>" alt="作品照片" class="img-fluid rounded-start shadow-lg" style="object-fit: cover; width:100%; height:100%;">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body mt-0 w-75 mx-auto">
                                <dl class="row g-5">
                                    <dt class="col-sm-4 fs-3">作品名稱 :</dt>
                                    <dd class="col-sm-8 fs-3 fw-bold"><?= $row["artname"] ?: "尚未提供" ?></dd>
                                    <dt class="col-sm-4 fs-3">作品簡介 :</dt>
                                    <dd class="col-sm-8 fs-3"></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <h2 class="text-center mt-5">使用者不存在</h2>
            <?php endif; ?>
        </div>
        <?php include("../footer.php") ?>
    </main>
    <?php include("../js.php") ?>
</body>

</html>