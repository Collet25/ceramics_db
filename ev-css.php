<style>
    body {
            margin: 50px;

            .card {
                width: 100%;
            }
        }

        html, body {
    height: 100%;
}

.main-content {
    display: flex;
    flex-direction: column;
    min-height: 100vh; /* 讓 main 至少跟視窗一樣高 */
}

.container-fluid {
    flex: 1; /* 讓內容區域可以撐開 main，使 footer 黏在底部 */
}

footer {
    text-align: center;
    padding: 10px 0;
}
</style>