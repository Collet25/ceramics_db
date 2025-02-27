<!-- 頁尾 -->
<footer class="footer pt-3">
    <div class="container-fluid">
        <div class="row justify-content-center text-center">
            <!-- Logo -->
            <div class="col-12 mb-3">
                <img src="../logo-img/logo-nav1.png" alt="logo" class="footer-logo">
            </div>
            <!-- 版權資訊 -->
            <div class="col-12">
                <div class="copyright">
                    Copyright © <script>document.write(new Date().getFullYear())</script>
                    <a href="#" class="text-secondary fw-bold">國立故瓷博物館</a>
                    by 萬磁王與小磁怪們
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
/* Footer 基本樣式 */
.footer {
    padding: 1.5rem 2rem;
    background-color: var(--main-bg);  /* 使用主背景色 */
    /*border-top: 1px solid var(--sub-bg);*/  /* 加入上邊框 */
    margin-top: auto;  /* 確保 footer 在底部 */
}

/* 版權文字 */
.copyright {
    color: var(--main-text) !important;  /* 使用主要文字顏色 */
    font-size: 0.875rem;
    line-height: 1.5;
    margin-top: 0.5rem;
}

/* 連結樣式 */
.copyright a {
    color: var(--primary) !important;  /* 使用主色 */
    text-decoration: none;
    transition: color 0.3s ease;
}

.copyright a:hover {
    color: var(--secondary) !important;  /* hover 時使用輔助色 */
}

/* Logo 樣式 */
.footer-logo {
    height: 70px;  /* 設定 Logo 高度 */
    width: auto;
    opacity: 0.95;  /* 稍微調低透明度 */
    transition: opacity 0.3s ease;
}

.footer-logo:hover {
    opacity: 1;  /* hover 時恢復完全不透明 */
}

/* 響應式調整 */
@media (max-width: 768px) {
    .footer {
        padding: 1rem;
    }
    
    .footer-logo {
        height: 45px;           /* 手機版調整大小 */
    }
    
    .copyright {
        font-size: 0.8rem;
    }
}
</style>





  
