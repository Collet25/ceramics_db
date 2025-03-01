<?php
require_once("../ceramics_db_connect.php");

if (!isset($_GET["id"])) {
    header("location: instructors.php");
}

$id = $_GET["id"];

$sql = "SELECT * FROM instructor WHERE id=$id";
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
    <link rel="apple-touch-icon" sizes="76x76" href="../logo-img/head-icon.png">
    <link rel="icon" type="image/png" href="../logo-img/head-icon.png">
    <!-- Bootstrap CSS v5.2.1 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous" />
    <?php include("../css.php") ?>
</head>

<body>
    <?php include("../aside.php") ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <?php
        $navbarTitle = "主頁";
        $navbarLink = "師資陣容";
        $navbarText = "資訊修改";
        include("../navbar.php"); ?>
        <div class="container-fluid py-4 px-5">
            <h2 class="text-center mb-4">資訊修改</h2>
            <div class="row">
                <div class="col-6 m-auto pt-3">
                    <?php if ($instructorCount > 0) : ?>
                        <form action="doUpdateinstructor.php" method="post" enctype="multipart/form-data">
                            <table class="table table-bordered table-hover shadow-lg">
                                <tr>
                                    <th>ID</th>
                                    <td class="text-start"> <input type="hidden" name="id" value="<?= $row["id"] ?>">
                                        <?= $row["id"] ?></td>
                                </tr>
                                <tr>
                                    <th>照片</th>
                                    <td>
                                        <img src="<?= $row["img"] ?>" alt="老師照片" class="rounded-3 shadow-lg img-fluid"
                                            id="previewImg" style="width: 300px; height: 300px;">
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
                                            <input class="form-check-input" type="radio" name="teacherGender" value="男"
                                                <?= ($row["gender"] == "男") ? "checked" : "" ?> required>
                                            <label class="form-check-label">男</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="teacherGender" value="女"
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
                            </table>
                        </form>
                    <?php else: ?>
                        <h2>使用者不存在</h2>
                    <?php endif; ?>
                </div>
            </div>
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
    </script>
    <?php include("../js.php") ?>
</body>

</html>