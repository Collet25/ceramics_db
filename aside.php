<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3" id="sidenav-main">
    <!-- Logo 區域 -->
    <div class="sidenav-header">
        <a class="navbar-brand " href="../index.php">
            <div class="logo-box mt-3">
                <img src="../logo-img/logo7.png" class="logo-img mb-5" alt="logo">
                <hr class="horizontal light "> 
            </div>
        </a>
    </div>

    <!-- <hr class="horizontal light"> -->

    <!-- 選單區域 -->
    <div class="collapse navbar-collapse w-auto ms-4" id="sidenav-collapse-main">
        <?php
        // 獲取當前頁面
        $currentPage = basename($_SERVER['PHP_SELF']);
        $currentDir = dirname($_SERVER['PHP_SELF']);
        
        // 定義頁面與選單的對應關係
        $menuActiveMap = [
            'users.php' => 'memberMenu',
            'frozenUsers.php' => 'memberMenu',

            'exhibition-list.php' => 'exhibitionMenu',
            'exhibition-create.php' => 'exhibitionMenu',

            'venue-list.php' => 'venueMenu',
            'venue-create.php' => 'venueMenu',
            'booking-list.php' => 'venueMenu',

            'instructors.php' => 'teacherMenu',
            'instructor-up.php' => 'teacherMenu',
            'delete-instructors.php' => 'teacherMenu',

            'product-list.php' => 'productMenu',
            'product-create.php' => 'productMenu',
            'product-trash.php' => 'productMenu',

            'coupon.php' => 'couponMenu',
            'create-coupon.php' => 'couponMenu',
        ];
        
        // 获取当前页面对应的菜单ID
        $activeMenu = isset($menuActiveMap[$currentPage]) ? $menuActiveMap[$currentPage] : '';
        ?>
        <ul class="navbar-nav">
            <!-- 會員管理 (可展開) -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" data-bs-target="#memberMenu" role="button" aria-expanded="false" aria-controls="memberMenu">
                    <div class="icon-wrapper me-2">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <span class="nav-link-text">會員管理</span>
                </a>

                <!-- 會員下拉選單 -->
                <div class="collapse <?= ($activeMenu === 'memberMenu') ? 'show' : '' ?>" id="memberMenu">
                    <ul class="navbar-nav ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-center <?= ($currentPage === 'users.php') ? 'active' : '' ?>" 
                               href="../user/users.php">會員列表</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($currentPage === 'frozenUsers.php') ? 'active' : '' ?>" 
                               href="../user/frozenUsers.php">帳號凍結</a>
                        </li>
                    </ul>
                </div>

            </li>

            <!-- 展覽管理 -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" data-bs-target="#exhibitionMenu" role="button" aria-expanded="false" aria-controls="exhibitionMenu">
                    <div class="icon-wrapper me-2">
                        <i class="fas fa-palette"></i>
                    </div>
                    <span class="nav-link-text">展覽管理</span>
                </a>

                <!-- 展覽下拉選單 -->
                <div class="collapse <?= ($activeMenu === 'exhibitionMenu') ? 'show' : '' ?>" id="exhibitionMenu">
                    <ul class="navbar-nav ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-center <?= ($currentPage === 'exhibition-list.php') ? 'active' : '' ?>" href="../exhibition/exhibition-list.php">展覽列表</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center <?= ($currentPage === 'exhibition-create.php') ? 'active' : '' ?>" href="../exhibition/exhibition-create.php">新增展覽</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="../exhibitions/exhibition-list.php">刪除展覽</a>
                        </li> -->
                    </ul>
                </div>
            </li>

            <!-- 場地管理 -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" data-bs-target="#venueMenu" role="button" aria-expanded="false" aria-controls="venueMenu">
                    <div class="icon-wrapper me-2">
                        <i class="bi bi-pin-map-fill"></i>
                    </div>
                    <span class="nav-link-text">場地管理</span>
                </a>

                <!-- 場地下拉選單 -->
                <div class="collapse <?= ($activeMenu === 'venueMenu') ? 'show' : '' ?>" id="venueMenu">
                    <ul class="navbar-nav ms-3">
                    <li class="nav-item">
                            <a class="nav-link text-center <?= ($currentPage === 'venue-list.php') ? 'active' : '' ?>" href="../venue/venue-list.php">場地列表</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center <?= ($currentPage === 'venue-create.php') ? 'active' : '' ?>" href="../venue/venue-create.php">新增場地</a>
                        </li>                       
                        <li class="nav-item">
                            <a class="nav-link <?= ($currentPage === 'booking-list.php') ? 'active' : '' ?>" href="../venue/booking-list.php">預約清單</a>
                        </li>   
                    </ul>
                </div>  
            </li>

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" data-bs-target="#teacherMenu" role="button" aria-expanded="false" aria-controls="teacherMenu">
                    <div class="icon-wrapper me-2">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <span class="nav-link-text">師資管理</span>
                </a>

                <!-- 師資下拉選單 -->
                <div class="collapse <?= ($activeMenu === 'teacherMenu') ? 'show' : '' ?>" id="teacherMenu">
                    <ul class="navbar-nav ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-center <?= ($currentPage === 'instructors.php') ? 'active' : '' ?>" 
                               href="../instructor/instructors.php">師資列表</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($currentPage === 'instructor-up.php') ? 'active' : '' ?>" 
                               href="../instructor/instructor-up.php">新增師資</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($currentPage === 'delete-instructors.php') ? 'active' : '' ?>" 
                               href="../instructor/delete-instructors.php">停權師資</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" data-bs-target="#productMenu" role="button" aria-expanded="false" aria-controls="productMenu">
                    <div class="icon-wrapper me-2">
                    <i class="bi bi-basket-fill"></i>
                    </div>
                    <span class="nav-link-text">商品管理</span>
                </a>

                <!-- 商品下拉選單 -->
                <div class="collapse <?= ($activeMenu === 'productMenu') ? 'show' : '' ?>" id="productMenu">
                    <ul class="navbar-nav ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-center <?= ($currentPage === 'product-list.php') ? 'active' : '' ?>" 
                               href="../products/product-list.php">商品列表</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($currentPage === 'product-create.php') ? 'active' : '' ?>" 
                               href="../products/product-create.php">新增商品</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($currentPage === 'product-trash.php') ? 'active' : '' ?>" 
                               href="../products/product-trash.php">已刪除商品</a>
                        </li>   
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="../products/product-delete.php">刪除商品</a>
                        </li> -->
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" data-bs-target="#couponMenu" role="button" aria-expanded="false" aria-controls="couponMenu">
                    <div class="icon-wrapper me-2">
                        <i class="fas fa-tags"></i>
                    </div>
                    <span class="nav-link-text">優惠券管理</span>
                </a>

                <!-- 優惠券下拉選單 -->
                <div class="collapse <?= ($activeMenu === 'couponMenu') ? 'show' : '' ?>" id="couponMenu">
                    <ul class="navbar-nav ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-center <?= ($currentPage === 'coupon.php') ? 'active' : '' ?>" 
                               href="../coupons/coupon.php">優惠券列表</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($currentPage === 'create-coupon.php') ? 'active' : '' ?>" 
                               href="../coupons/create-coupon.php">新增優惠券</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="../coupons/coupon-list.php">刪除優惠券</a>
                        </li> -->
                    </ul>
                </div>  
            </li>
        </ul>
    </div>

    <!-- 登出區域 -->
    <div class="sidenav-footer position-absolute w-100 bottom-0">
        <div class="mx-3">
            <a class="btn btn-primary mt-4 w-100" href="../user/sign-in.php" type="button">
                <i class="fas fa-sign-out-alt me-2"></i> 登出
            </a>
        </div>
    </div>
</aside>

<style>
/* 側邊欄基本樣式 */
.sidenav {
    background-color: #2D2D2D;
    z-index: 1024;
    transition: all 0.3s ease;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    width: 250px !important;
}

/* Logo 區域 */
.sidenav-header {
    padding: 0;
    border-bottom: none;
    margin-bottom: 150px;
}

.navbar-brand {
    width: 100%;
    margin: 0 !important;
    padding: 0 !important;
}

/* Logo 容器 */
.logo-box {
    width: 100%;
    height: 200px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    overflow: visible;
    position: relative;
}

/* Logo 圖片 */
.logo-img {
    max-width: 85%;
    height: auto;
    object-fit: contain;
    margin-bottom: 2rem;
    margin-top: 2rem;
}

/* 分隔線樣式 */
.horizontal.light {
    width: 80%;
    margin: 0;
    opacity: 0.2;
    border-color: #fff;
    position: absolute;
    bottom: 0;
}

/* 選單容器 */
.navbar-collapse {
    margin-top: 1.5rem;
    padding-top: 0.75rem;
}

/* 選單項目樣式 */
.nav-link {
    color: #D1D5DB !important;
    margin: 0.75rem 0.75rem;
    border-radius: 0.5rem;
    padding: 0.75rem 0.875rem;
    transition: all 0.2s ease;
}

.nav-link:hover:not(.active) {
    background-color: #EA580C !important;
    color: #FFFFFF !important;
}

.nav-link.active {
    background-color: #9A3412 !important;
    color: #FFFFFF !important;
}

/* 圖示包裝 */
.icon-wrapper {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.5rem;
    background-color: rgba(255, 255, 255, 0.1);
    margin-right: 0.5rem !important;
}

.nav-link i {
    font-size: 1.1rem;
    color: #F5E3C3;
}

.nav-link:hover .icon-wrapper {
    background-color: rgba(255, 255, 255, 0.2);
}

.nav-link.active .icon-wrapper {
    background-color: #F5E3C3;
}

.nav-link.active i {
    color: #9A3412;
}

/* 登出按鈕 */
.sidenav-footer {
    padding: 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.sidenav-footer .btn-primary {
    background-color: #9A3412;
    border: none;
    transition: all 0.2s ease;
}

.sidenav-footer .btn-primary:hover {
    background-color: #EA580C;
}

/* 主內容區域調整 */
.main-content {
    margin-left: 250px !important;
}

/* 響應式調整 */
@media (max-width: 768px) {
    .sidenav {
        transform: translateX(-100%);
    }
    
    .g-sidenav-show .sidenav {
        transform: translateX(0);
    }
    .main-content {
        margin-left: 0 !important;
    }
    .logo-box {
        height: 120px;
    }
}
</style>

