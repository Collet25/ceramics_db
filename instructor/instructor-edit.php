<?php
require_once("../ceramics_db_connect.php");

if (!isset($_GET["id"])) {
    header("location: instructors.php");
}

$id = $_GET["id"];
$sql = "SELECT instructor.*,
               artwork.artname,
               artwork.image AS artwork_image,
               artwork.artbio
        FROM instructor
        LEFT JOIN artwork ON instructor.id = artwork.instructor_id
        WHERE instructor.id = $id
        AND instructor.valid = 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$instructorCount = $result->num_rows;
// var_dump($row);
?>
<!doctype html>
<html lang="en">

<head>
    <title>資訊修改</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="apple-touch-icon" sizes="76x76" href="../logo-img/head-icon.png">
    <link rel="icon" type="image/png" href="../logo-img/head-icon.png">
    <?php include("../css.php") ?>
    <style>
        #ArtPreviewImg {
            width: 300px;
            height: 300px;
            object-fit: cover;
            display: none;
            border: 2px solid #ddd;
            padding: 3px;
        }
    </style>
</head>

<body>
    <?php include("../aside.php") ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <?php include("../navbar.php"); ?>
        <div class="container py-4 px-5">
            <h2 class="text-center mb-4">資訊修改</h2>
            <?php if ($instructorCount > 0) : ?>
                <form action="doUpdateinstructor.php" method="post" enctype="multipart/form-data">
                    <div class="mb-2">
                        <button type="submit" class="btn btn-primary mt-3">更新資料</button>
                        <a href="./instructor.php?id=<?= $row["id"] ?>" class="btn btn-secondary mt-3">取消</a>
                    </div>
                    <div class="card mb-3">
                        <div class="row g-0 align-items-center">
                            <div class="col-md-4">
                                <img src="<?= $row["img"] ?>" alt="老師照片" class="img-fluid rounded-start shadow-lg" style="object-fit: cover; width:100%; height:100%;" id="previewImg">
                                <input type="file" class="form-control my-3" name="teacherImg" id="teacherImg"
                                    accept="image/*" onchange="previewImage(event)">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body w-75 mx-auto">
                                    <dl class="row g-3">
                                        <dt class="col-sm-3">ID</dt>
                                        <dd class="col-sm-9"><input type="hidden" name="id" value="<?= $row["id"] ?>">
                                            <?= $row["id"] ?></dd>
                                        <hr class="mt-2">
                                        <dt class="col-sm-3">姓名</dt>
                                        <dd class="col-sm-9"><input type="text" class="form-control" name="teacherName" value="<?= $row["name"] ?>" required></dd>
                                        <hr class="mt-2">
                                        <dt class="col-sm-3">性別</dt>
                                        <dd class="col-sm-9">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input d-flex align-items-center justify-content-center" type="radio" name="teacherGender" value="男"
                                                    <?= ($row["gender"] == "男") ? "checked" : "" ?> required>
                                                <label class="form-check-label">男</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input d-flex align-items-center justify-content-center" type="radio" name="teacherGender" value="女"
                                                    <?= ($row["gender"] == "女") ? "checked" : "" ?> required>
                                                <label class="form-check-label">女</label>
                                            </div>
                                        </dd>
                                        <hr class="mt-2">
                                        <dt class="col-sm-3">Email</dt>
                                        <dd class="col-sm-9"><input type="email" class="form-control" name="teacherEmail" value="<?= $row["email"] ?>" required></dd>
                                        <hr class="mt-2">
                                        <dt class="col-sm-3">手機</dt>
                                        <dd class="col-sm-9"><input type="text" class="form-control" name="teacherPhone" value="<?= $row["phone"] ?>" required></dd>
                                        <hr class="mt-2">
                                        <dt class="col-sm-3">簡介</dt>
                                        <dd class="col-sm-9"><textarea class="form-control" name="teacherBio" rows="3" required><?= $row["bio"] ?></textarea></dd>
                                        <hr class="mt-2">
                                        <dt class="col-sm-3">加入時間</dt>
                                        <dd class="col-sm-9"><?= $row["created_at"] ?></dd>
                                        <hr class="mt-2">
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 下-->
                    <h2 class="text-center mt-5 mb-4">主要作品</h2>
                    <div class="card mb-3">
                        <div class="row g-0 align-items-center">
                            <div class="col-md-4">
                                <img src="<?= $row["artwork_image"] ?>" alt="作品照片" class="img-fluid rounded-start shadow-lg" style="object-fit: cover; width:100%; height:100%;" id="ArtPreviewImg">
                                <input type="file" class="form-control my-3" name="artImg" id="artImg"
                                    accept="image/*" onchange="ArtpreviewImage(event)">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body mt-0 w-75 mx-auto">
                                    <dl class="row g-5">
                                        <dt class="col-sm-4 fs-3">作品名稱:</dt>
                                        <dd class="col-sm-8 fs-3"><input type="text" class="form-control" name="artName" value="<?= $row["artname"] ?>" required></dd>
                                        <dt class="col-sm-4 fs-3">作品介紹:</dt>
                                        <dd class="col-sm-8 fs-3"><textarea class="form-control" name="artBio" rows="3" required><?= $row["artbio"] ?></textarea></dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 舊 -->
                    <!-- <table class="table table-bordered table-hover shadow-lg">
                                <tr>
                                    <th>ID</th>
                                    <td class="text-start"> <input type="hidden" name="id" value="<?= $row["id"] ?>">
                                        <?= $row["id"] ?></td>
                                </tr>
                                <tr>
                                    <th>照片</th>
                                    <td>
                                        <img src="<?= $row["img"] ?>" alt="老師照片" class="rounded-3 shadow-lg img-fluid"
                                            id="previewImg" style="width: 250px; height: auto;">
                                        <input type="file" class="form-control mt-2" name="teacherImg" id="teacherImg"
                                            accept="image/*" onchange="previewImage(event)">
                                    </td>
                                </tr>
                                <tr>
                                    <th>姓名</th>
                                    <td><input type="text" class="form-control" name="teacherName" value="<?= $row["name"] ?>" required></td>
                                </tr>
                                <tr>
                                    <th>性別</th>
                                    <td class="text-start">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input d-flex align-items-center justify-content-center" type="radio" name="teacherGender" value="男"
                                                <?= ($row["gender"] == "男") ? "checked" : "" ?> required>
                                            <label class="form-check-label">男</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input d-flex align-items-center justify-content-center" type="radio" name="teacherGender" value="女"
                                                <?= ($row["gender"] == "女") ? "checked" : "" ?> required>
                                            <label class="form-check-label">女</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><input type="email" class="form-control" name="teacherEmail" value="<?= $row["email"] ?>" required></td>
                                </tr>
                                <tr>
                                    <th>手機</th>
                                    <td><input type="text" class="form-control" name="teacherPhone" value="<?= $row["phone"] ?>" required></td>
                                </tr>
                                <tr>
                                    <th>簡介</th>
                                    <td><textarea class="form-control" name="teacherBio" rows="3" required><?= $row["bio"] ?></textarea></td>
                                </tr>
                                <tr>
                                    <th>創建時間</th>
                                    <td class="text-start"><?= $row["created_at"] ?></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <button type="submit" class="btn btn-primary mt-3">更新資料</button>
                                        <a href="./instructor.php?id=<?= $row["id"] ?>" class="btn btn-primary mt-3">取消</a>
                                    </td>
                                </tr>
                            </table> -->
                </form>
            <?php else: ?>
                <h2>使用者不存在</h2>
            <?php endif; ?>
        </div>
        <?php include("../footer.php") ?>
    </main>
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var preview = document.getElementById('previewImg');
                preview.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        function ArtpreviewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var preview = document.getElementById('ArtPreviewImg');
                preview.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
    <?php include("../js.php") ?>
</body>

</html>