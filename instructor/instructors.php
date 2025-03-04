<?php
require_once("../ceramics_db_connect.php");

$sqlAll = "SELECT * FROM instructor WHERE valid=1";
$resultAll = $conn->query($sqlAll);
$instructorCount = $resultAll->num_rows;

$sql = "SELECT instructor.*, artwork.artname, artwork.image 
        FROM instructor 
        LEFT JOIN artwork ON instructor.id = artwork.instructor_id
        WHERE instructor.valid=1";

if (isset($_GET["q"])) {
    $q = $_GET["q"];
    $sql .= " AND (instructor.name LIKE '%$q%' OR instructor.phone LIKE '%$q%')";
} else if (isset($_GET["P"]) && isset($_GET["order"])) {
    $P = $_GET["P"];
    $order = $_GET["order"];
    $orderClause = "";

    switch ($order) {
        case 1:
            $orderClause = "ORDER BY instructor.id ASC";
            break;
        case 2:
            $orderClause = "ORDER BY instructor.id DESC";
            break;
        case 3:
            $orderClause = "ORDER BY instructor.created_at ASC";
            break;
        case 4:
            $orderClause = "ORDER BY instructor.created_at DESC";
            break;
    }

    $perPage = 6;
    $startItem = ($P - 1) * $perPage;
    $totalPage = ceil($instructorCount / $perPage);

    $sql .= " $orderClause LIMIT $startItem, $perPage";
} else {
    header("location:instructors.php?P=1&order=1");
    // $sql = "SELECT * FROM instructor WHERE valid=1";
}

$result = $conn->query($sql);
$rows = $result->fetch_all(MYSQLI_ASSOC);
if (isset($_GET["q"])) {
    $instructorCount = $result->num_rows;
}
// var_dump($rows);
?>
<!doctype html>
<html lang="en">

<head>
    <title>老師名單</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../logo-img/head-icon.png">
    <?php include("../css.php") ?>
    <style>
        .btn:focus,
        .btn:active {
            box-shadow: none !important;
            outline: none !important;

        }
        .table td,
        .table th {
            word-break: break-word;
            white-space: normal;
            max-width: 400px;
            /* 依需求調整 */
            overflow-wrap: break-word;
        }
    </style>
</head>

<body>
    <!-- 刪除Modal -->
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content w-50 m-auto">
                <div class="modal-header">
                    <h5 class="modal-title " id="exampleModalLabel">系統訊息</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center h-50">
                    確認要刪除這位老師嗎？
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <a role="button" class="btn btn-danger" id="confirmDelete">確認</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
                </div>
            </div>
        </div>
    </div>
    <!-- 老師頭像 Modal -->
    <div class="modal fade" id="instructorModal" tabindex="-1" aria-labelledby="instructorModalLabel" aria-hidden="true">
        <div class=" modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="instructorModalLabel">老師照片</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center d-block">
                    <h5 class="fs-2" id="modalInstructorFullName"></h5>
                    <hr class="border-3">
                    <img class="d-block mx-auto img-fluid" id="modalInstructorImage" src="" alt="老師頭像" style="width: 320px; height: auto;">
                </div>
            </div>
        </div>
    </div>
    <!-- 作品 Modal -->
    <div class="modal fade" id="artworkModal" tabindex="-1" aria-labelledby="artworkModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5 class="modal-title" id="artworkModalLabel">主要作品</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-block">
                    <h5 class="text-center fs-2" id="modalInstructorName"></h5>
                    <hr class="border-3">
                    <p class="text-center fs-3" id="modalArtworkName"></p>
                    <img class="d-block mx-auto img-fluid" id="modalArtworkImage" src="" alt="作品圖片" style="width: 280px; height: auto;">
                </div>
            </div>
        </div>
    </div>
    <?php include("../aside.php") ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <?php include("../navbar.php"); ?>
        <div class="container-fluid py-4 px-5">
            <!-- 主要內容 -->
            <div class="row">
                <div class="col-12">
                    <div class="card border shadow-xs mb-4">
                        <div class="card-header border-bottom pb-0">
                            <div class="d-sm-flex align-items-center">
                                <div>
                                    <h6 class="font-weight-semibold fs-2 mb-0"><i class="fa-solid fa-user-graduate fw-fs"></i>老師名單</h6>
                                    <p class="text-sm mt-2">查看所有老師的信息</p>
                                </div>
                                <div class="ms-auto d-flex">
                                    <a href="./delete-instructors.php" class="btn btn-primary me-3"><i class="fa-solid fa-user-xmark pe-2"></i>查看刪除名單</a>
                                    <a href="./instructor-up.php" class="btn btn-primary"><i class="fa-solid fa-user-plus pe-2"></i>新增老師</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 py-0">
                            <div class="border-bottom py-3 px-3 d-sm-flex align-items-center justify-content-between">
                                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                    <a href="./instructors.php?P=<?= $P ?>&order=1" class="btn btn-light text-dark <?php if ($order == 1) echo "active" ?>">ID<i class="fa-solid fa-arrow-down-1-9"></i></a>
                                    <a href="./instructors.php?P=<?= $P ?>&order=2" class="btn btn-light text-dark <?php if ($order == 2) echo "active" ?>">ID<i class="fa-solid fa-arrow-down-9-1"></i></a>
                                    <a href="./instructors.php?P=<?= $P ?>&order=3" class="btn btn-light text-dark <?php if ($order == 3) echo "active" ?>">加入時間舊到新<i class="fa-solid fa-down-long"></i></a>
                                    <a href="./instructors.php?P=<?= $P ?>&order=4" class="btn btn-light text-dark <?php if ($order == 4) echo "active" ?>">加入時間新到舊<i class="fa-solid fa-down-long"></i></a>
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
                                    <a href="./instructors.php" class="btn btn-primary"><i class="fa-solid fa-arrow-left pe-2"></i>回老師名單</a>
                                <?php endif; ?>
                                <div class="mt-3 px-3"> 共<?= $instructorCount ?>位使用者</div>
                            </div>
                            <div class="table-responsive p-0">
                                <?php if ($instructorCount > 0): ?>
                                    <table class="table align-items-center mb-0">
                                        <thead class="bg-gray-200">
                                            <tr>
                                                <th class="text-center">ID</th>
                                                <th class="text-start">姓名/email</th>
                                                <th class="text-start">預覽作品</th>
                                                <th class="text-start">性別</th>
                                                <th class="text-start">電話</th>
                                                <th class="text-start">簡介</th>
                                                <th class="text-start">加入時間</th>
                                                <th class="text-start">操作功能</th>
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
                                                                <!-- 點頭像出作品 -->
                                                                <a href="#" class="view-instructor"
                                                                    data-name="<?= $row["name"] ?>"
                                                                    data-image="<?= $row["img"] ?>"
                                                                    data-bs-toggle="modal" data-bs-target="#instructorModal">
                                                                    <img class="avatar avatar-sm rounded-circle me-2" src="<?= $row["img"] ?>" alt="user1">
                                                                </a>
                                                            </div>
                                                            <div class="d-flex flex-column justify-content-center ms-1">
                                                                <h6 class="mb-0 text-sm font-weight-semibold text-start"><?= $row["name"] ?></h6>
                                                                <p class="text-sm text-secondary mb-0"><?= $row["email"] ?></p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="" class="view-artwork btn btn-danger"
                                                            data-name="<?= $row["name"] ?>"
                                                            data-artname="<?= $row["artname"] ?? '暫無作品' ?>"
                                                            data-image="<?= $row["image"] ?? 'default.jpg' ?>"
                                                            data-bs-toggle="modal" data-bs-target="#artworkModal">
                                                            <i class="fa-solid fa-palette"></i></a>
                                                    </td>
                                                    <td>
                                                        <p class="fw-bold text-start"><?= $row["gender"] ?></p>
                                                    </td>
                                                    <td>
                                                        <p class="fw-bold text-start"><?= $row["phone"] ?></p>
                                                    </td>
                                                    <td>
                                                        <p class="fw-bold text-start"><?= $row["bio"] ?></p>
                                                    </td>
                                                    <td>
                                                        <p class="fw-bold text-start"><?= $row["created_at"] ?></p>
                                                    </td>
                                                    <td class="align-middle text-start">
                                                        <a href="../instructor/instructor.php?id=<?= $row["id"] ?>" class="px-3 btn btn-primary"><i class="fa-solid fa-eye"></i></a>
                                                        <a href="" class="px-3 delete-btn btn btn-danger" data-id="<?= $row["id"] ?>" data-bs-toggle="modal" data-bs-target="#infoModal">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
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
                                                <li class="page-item <?= $active ?>"><a class="page-link border-0 font-weight-bold" href="./instructors.php?P=<?= $i ?>&order=<?= $order ?>"><?= $i ?></a></li>
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
<script>
    let deleteId = null;

    // 監聽所有刪除按鈕，當按下時，更新 deleteId
    document.querySelectorAll(".delete-btn").forEach(button => {
        button.addEventListener("click", function() {
            deleteId = this.getAttribute("data-id"); // 獲取 data-id
        });
    });

    // 當點擊「確認刪除」時，跳轉到刪除頁面
    document.getElementById("confirmDelete").addEventListener("click", function() {
        if (deleteId) {
            window.location.href = "../instructor/instructorDelete.php?id=" + deleteId;
        }
    });
    // 監聽所有老師頭像的點擊事件
    document.querySelectorAll(".view-instructor").forEach(button => {
        button.addEventListener("click", function() {
            let instructorName = this.getAttribute("data-name");
            let instructorImage = this.getAttribute("data-image");

            document.getElementById("modalInstructorFullName").textContent = instructorName;
            document.getElementById("modalInstructorImage").src = instructorImage ? instructorImage : "default.jpg";
        });
    });
    // 作品
    document.querySelectorAll(".view-artwork").forEach(button => {
        button.addEventListener("click", function() {
            let instructorName = this.getAttribute("data-name");
            let artworkName = this.getAttribute("data-artname");
            let artworkImage = this.getAttribute("data-image");

            document.getElementById("modalInstructorName").textContent = "老師名字：" + instructorName;
            document.getElementById("modalArtworkName").textContent = "作品名稱：" + (artworkName ? artworkName : "這位老師暫無作品");
            document.getElementById("modalArtworkImage").src = artworkImage ? artworkImage : "default.jpg";
        });
    });
</script>

</html>