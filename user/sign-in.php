<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php include("../css.php") ?>
    <style>
        body {
            background-image: url('../logo-img/bg2.webp');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            /* 確保 body 至少跟視窗一樣高 */
        }

        .row {
            min-height: 100vh;
            /* 讓 `.row` 填滿整個視窗高度 */
            display: flex;
            align-items: center;
            /* 垂直置中 */
            justify-content: center;
            /* 水平置中（可選） */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4 col-sm-8 col-12 d-flex flex-column mx-auto">
                <div class="card" style="background-color:rgba(255, 255, 255, 0.8);">
                    <div class="card-header pb-0 text-left bg-transparent">
                        <h3 class="font-weight-black text-dark display-6">登入</h3>
                    </div>

                    <div class="card-body">
                        <form action="doSignIn.php" method="post">
                            <div class="mb-3">
                                <label for="account" class="form-label">帳號</label>
                                <input type="text" class="form-control" placeholder="請輸入您的帳號" name="account" id="account" required minlength="3" maxlength="20">
                            </div>
                            <div class="mb-3">
                                <label for="psaaword" class="form-label">密碼</label>
                                <input type="password" class="form-control" placeholder="請輸入您的密碼" name="password" id="password" required
                                    minlength="5" maxlength="20">
                            </div>
                            <!-- <div class="mb-3">
                                <label for="repassword" class="form-label">確認密碼</label>
                                <input type="password" class="form-control" placeholder="請確認您的密碼" name="repassword" id="repassword" required
                                minlength="5" maxlength="20">
                            </div> -->
                            <!-- <div class="d-flex align-items-center">
                                <div class="form-check form-check-info text-left mb-0">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="font-weight-normal text-dark mb-0" for="flexCheckDefault">
                                        記住我
                                    </label>
                                </div>
                                <a href="javascript:;" class="text-xs font-weight-bold ms-auto">忘記密碼</a>
                            </div> -->
                            <div class="text-center">
                                <button class="btn btn-dark w-100 mt-4 mb-3" type="submit">登入</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center px-lg-2 px-1">
                        <p class="mb-4 mx-auto">
                            還沒有帳號?
                            <a href="sign-up.php" class="text-dark font-weight-bold">註冊</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        <?php include("js.php") ?>

        <?php if (isset($_GET['error']) && $_GET['error'] != ''): ?>
            alert("<?= $_GET['error'] ?>"); // 顯示錯誤訊息
        <?php endif; ?>
    </script>


</body>

</html>