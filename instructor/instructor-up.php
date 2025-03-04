<?php
require_once("../ceramics_db_connect.php");

?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <title>新增老師</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="apple-touch-icon" sizes="76x76" href="../logo-img/head-icon.png">
    <link rel="icon" type="image/png" href="../logo-img/head-icon.png">
    <?php include("../css.php") ?>
    <style>
        #previewImg {
            width: 300px;
            height: 300px;
            object-fit: cover;
            display: none;
            border: 2px solid #ddd;
            padding: 3px;
        }

        #artworkImg {
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
            <div class="card shadow-sm p-4 w-75 mx-auto">
                <h2 class="text-center fs-1 mb-4">新增老師</h2>
                <hr class="mt-0 border-2">
                <form action="add-instructor.php" method="POST" enctype="multipart/form-data">
                    <a href="./instructors.php" class="btn btn-primary"><i class="fa-solid fa-arrow-left pe-2"></i>回成員名單</a>
                    <div class="mb-3">
                        <label class="form-label">老師照片</label>
                        <div class="d-flex align-items-center">
                            <input type="file" class="form-control me-3" name="teacherImg" id="teacherImg" accept="image/*" required onchange="previewImage(event)">
                            <img id="previewImg" class="" src="#" alt="預覽圖片">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="teacherName" class="form-label">老師姓名</label>
                        <input type="text" class="form-control" name="teacherName" required>
                    </div>
                    <div class="mb-3">
                        <label for="teacherEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" name="teacherEmail" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">性別</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input d-flex align-items-center justify-content-center" type="radio" name="teacherGender" value="男" required>
                            <label class="form-check-label">男</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input d-flex align-items-center justify-content-center" type="radio" name="teacherGender" value="女" required>
                            <label class="form-check-label">女</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="teacherPhone" class="form-label">手機號碼</label>
                        <input type="text" class="form-control" name="teacherPhone" required>
                    </div>
                    <div class="mb-3">
                        <label for="teacherBio" class="form-label">老師簡介</label>
                        <textarea class="form-control" name="teacherBio" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-2">主要作品:</label>
                        <div class="d-flex align-items-center">
                            <input type="file" class="form-control me-3" name="artImg" id="artImg" accept="image/*" required onchange="AetworkImage(event)">
                            <img id="artworkImg" style="width: 200px; height: 200px;" class="" src="#" alt="">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="artName" class="form-label">作品名字</label>
                        <input type="text" class="form-control" name="artName" required>
                    </div>
                    <div class="mb-3">
                        <label for="artBio" class="form-label">作品簡介</label>
                        <textarea class="form-control" name="artBio" rows="3" required></textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary w-25">新增老師</button>
                    </div>
                </form>
            </div>
        </div>
        <script>
            function previewImage(event) {
                var reader = new FileReader();
                reader.onload = function() {
                    var preview = document.getElementById('previewImg');
                    preview.src = reader.result;
                    preview.style.display = "block";
                };
                reader.readAsDataURL(event.target.files[0]);
            }

            function AetworkImage(event) {
                var reader = new FileReader();
                reader.onload = function() {
                    var preview = document.getElementById('artworkImg');
                    preview.src = reader.result;
                    preview.style.display = "block";
                };
                reader.readAsDataURL(event.target.files[0]);
            }
        </script>
        <?php include("../footer.php") ?>
        <?php include("../js.php") ?>
    </main>
</body>

</html>