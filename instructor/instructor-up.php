<?php
require_once("../ceramics_db_connect.php");

?>

<!DOCTYPE html>
<html lang="zh-TW">

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
    <style>
        #previewImg {
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
        <?php
        $navbarTitle = "『國立故瓷博物館』後台系統";
        $navbarLink = "師資管理";
        $navbarText = "新增老師";
        include("../navbar.php"); ?>
        <div class="container py-4 px-5">
            <h2 class="text-center mb-4">新增老師</h2>
            <div class="card shadow-sm p-4 w-75 mx-auto">
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
                            <input class="form-check-input" type="radio" name="teacherGender" value="男" required>
                            <label class="form-check-label">男</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="teacherGender" value="女" required>
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
        </script>
        <?php include("../footer.php") ?>
        <?php include("../js.php") ?>
    </main>
</body>

</html>