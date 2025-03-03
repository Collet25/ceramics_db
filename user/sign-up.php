<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>


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
      min-height: 100vh;
    }

    .error-message {
      color: red;
      font-size: 14px;
      margin-top: 5px;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="row">
      <div class="col-md-6 d-flex mx-auto">
        <div class="card mt-5 mb-5" style="background-color:rgba(255, 255, 255, 0.8);">
          <div class="card-header pb-0 text-left bg-transparent">
            <h3 class="font-weight-black text-dark display-6">註冊</h3>
          </div>

          <div class="card-body">
            <form action="doSignUp.php" method="post">
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="account" class="form-label">帳號</label>
                  <input type="text" class="form-control" placeholder="請輸入3~20字元的帳號" name="account" id="account">

                  <?php if (isset($_GET['error']) && $_GET['error'] == '該帳號已註冊'): ?>
                    <div class="error-message" id="errorMessage">該帳號已註冊</div>
                  <?php endif; ?>

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
                    <input class="form-check-input d-flex justify-content-center align-items-center" type="radio" name="gender" id="male" value="男性" checked>
                    <label class="form-check-label" for="male">男性</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input d-flex justify-content-center align-items-center" type="radio" name="gender" id="female" value="女性">
                    <label class="form-check-label" for="female">女性</label>
                  </div>
                </div>
              </div>
              <div class="text-center">
                <button class="btn btn-dark w-100 mt-4 mb-3" type="submit">註冊</button>
              </div>
            </form>
          </div>
          <div class="card-footer text-center px-lg-2 px-1">
            <p class="mx-auto py-2">
              已經是會員了?
              <a href="sign-in.php" class="text-dark font-weight-bold">登入</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>


  <script>
    setTimeout(function() {
      var errorMessage = document.getElementById("errorMessage");
      if (errorMessage) {
        errorMessage.style.display = "none";

        // 移除 URL 內的 error 參數
        var url = new URL(window.location.href);
        url.searchParams.delete("error");
        window.history.replaceState({}, document.title, url.toString());
      }
    }, 3000);
  </script>

</body>

</html>