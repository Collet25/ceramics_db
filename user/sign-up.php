<!--
=========================================================
* Corporate UI - v1.0.0
=========================================================

* Product Page: https://www.creative-tim.com/product/corporate-ui
* Copyright 2022 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
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
      <div class="col-md-6 d-flex flex-column mx-auto">
        <div class="card mt-5" style="background-color:rgba(255, 255, 255, 0.8);">
          <div class="card-header pb-0 text-left bg-transparent">
            <h3 class="font-weight-black text-dark display-6">註冊</h3>
          </div>

          <div class="card-body">
            <form action="doSignUp.php" method="post">
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="account" class="form-label">帳號</label>
                  <input type="text" class="form-control" placeholder="請輸入3~20字元的帳號" name="account" id="account">
                </div>
                <div class="col-md-6">
                  <label for="password" class="form-label">密碼</label>
                  <input type="password" class="form-control" placeholder="請輸入5~20字元的密碼" name="password" id="password">
                </div>
              </div>
              <div class="mb-2">
                <label for="name" class="form-label">姓名</label>
                <input type="text" class="form-control" placeholder="請輸入您的姓名" name="name" id="name">
              </div>
              <div class="mb-2">
                <label for="email" class="form-label">信箱</label>
                <input type="email" class="form-control" placeholder="請輸入您的信箱" name="email" id="email">
              </div>
              <div class="mb-2">
                <label for="phone" class="form-label">電話</label>
                <input type="tel" class="form-control" placeholder="請輸入您的電話號碼" name="phone" id="phone">
              </div>
              <div class="mb-2">
                <label for="birth" class="form-label">生日</label>
                <input type="date" class="form-control" placeholder="請輸入您的生日" name="birth" id="birth">
              </div>
              <div class="">
                <label for="">性別</label>
                <div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="male" value="男性" checked>
                    <label class="form-check-label" for="male">男性</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="female" value="女性">
                    <label class="form-check-label" for="female">女性</label>
                  </div>
                </div>
              </div>
              <div class="text-center">
                <button class="btn btn-dark w-100 mt-4 mb-3" type="submit">註冊</button>
              </div>
            </form>
          </div>
          <div class="card-footer text-center pt-0 px-lg-2 px-1">
            <p class=" text-xs mx-auto">
              已經是會員了?
              <a href="sign-in.php" class="text-dark font-weight-bold">登入</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>


  <script>
    <?php include("js.php") ?>
  </script>
  <!--   Core JS Files   -->
  <!-- <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Corporate UI Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/corporate-ui-dashboard.min.js?v=1.0.0"></script> -->
</body>

</html>