<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Noto+Sans:300,400,500,600,700,800|PT+Mono:300,400,500,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/349ee9c857.js" crossorigin="anonymous"></script>
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <!-- <link id="pagestyle" href="../assets/css/corporate-ui-dashboard.css?v=1.0.0" rel="stylesheet" /> -->
    <link rel="stylesheet" href="/corporate/assets/css/corporate-ui-dashboard.css">
    <style>
        body {
            background-image: url('/corporate/assets/img/bg.png');
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 d-flex flex-column mx-auto">
                <div class="card mt-5" style="background-color:rgba(255, 255, 255, 0.8);">
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
                            <div class="mb-3">
                                <label for="repassword" class="form-label">確認密碼</label>
                                <input type="password" class="form-control" placeholder="請確認您的密碼" name="repassword" id="repassword" required
                                minlength="5" maxlength="20">
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="form-check form-check-info text-left mb-0">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="font-weight-normal text-dark mb-0" for="flexCheckDefault">
                                        記住我
                                    </label>
                                </div>
                                <a href="javascript:;" class="text-xs font-weight-bold ms-auto">忘記密碼</a>
                            </div>
                            <div class="text-center">
                                <button class="btn btn-dark w-100 mt-4 mb-3" type="submit">登入</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center pt-0 px-lg-2 px-1">
                        <p class="mb-4 text-xs mx-auto">
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
    </script>

    
</body>

</html>