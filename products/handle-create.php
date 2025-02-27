<?php
require_once("../ceramics_db_connect.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    try {
        // 設定上傳目錄
        $target_dir = "../uploads/";
        
        // 檢查上傳目錄是否存在
        if(!file_exists($target_dir)){
            throw new Exception("上傳目錄不存在，請聯繫管理員");
        }

        // 檢查目錄是否可寫入
        if(!is_writable($target_dir)){
            throw new Exception("上傳目錄無寫入權限，請聯繫管理員");
        }

        // 檢查檔案上傳
        if(!isset($_FILES["image"]) || $_FILES["image"]["error"] !== UPLOAD_ERR_OK){
            $error_messages = [
                UPLOAD_ERR_INI_SIZE => "上傳的檔案超過了 php.ini 中 upload_max_filesize 的限制",
                UPLOAD_ERR_FORM_SIZE => "上傳的檔案超過了表單中 MAX_FILE_SIZE 的限制",
                UPLOAD_ERR_PARTIAL => "檔案只有部分被上傳",
                UPLOAD_ERR_NO_FILE => "沒有檔案被上傳",
                UPLOAD_ERR_NO_TMP_DIR => "找不到暫存資料夾",
                UPLOAD_ERR_CANT_WRITE => "檔案寫入失敗",
                UPLOAD_ERR_EXTENSION => "PHP 擴充功能停止了檔案上傳"
            ];
            $error_message = isset($error_messages[$_FILES["image"]["error"]]) 
                ? $error_messages[$_FILES["image"]["error"]] 
                : "未知的上傳錯誤";
            throw new Exception($error_message);
        }

        // 檢查檔案類型
        $allowed_types = ["image/jpeg", "image/png", "image/gif"];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $_FILES["image"]["tmp_name"]);
        finfo_close($finfo);

        if(!in_array($mime_type, $allowed_types)){
            throw new Exception("只允許上傳 JPG, PNG 或 GIF 格式的圖片");
        }

        // 生成唯一的檔案名稱
        $image_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $image_name = uniqid() . "." . $image_extension;
        $target_file = $target_dir . $image_name;

        // 移動檔案
        if(!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){
            throw new Exception("檔案移動失敗，請檢查目錄權限");
        }

        // 檢查其他必填欄位
        $required_fields = ["name", "category", "subcategory", "price", "description", "material", "origin"];
        foreach($required_fields as $field){
            if(empty($_POST[$field])){
                // 如果檔案已上傳成功但其他欄位有誤，刪除已上傳的檔案
                if(file_exists($target_file)){
                    unlink($target_file);
                }
                throw new Exception("請填寫所有必填欄位");
            }
        }

        // 新增商品資料
        $sql = "INSERT INTO products (name, category, subcategory, price, description, image, material, origin) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssissss", 
            $_POST["name"],
            $_POST["category"],
            $_POST["subcategory"],
            $_POST["price"],
            $_POST["description"],
            $image_name,
            $_POST["material"],
            $_POST["origin"]
        );

        if(!$stmt->execute()){
            // 如果資料庫新增失敗，刪除已上傳的檔案
            if(file_exists($target_file)){
                unlink($target_file);
            }
            throw new Exception("商品新增失敗：" . $stmt->error);
        }

        echo "<script>
            alert('商品新增成功！');
            location.href = 'product-list.php';
        </script>";

    } catch (Exception $e) {
        // 顯示詳細的錯誤訊息
        echo "<script>
            console.error('" . addslashes($e->getMessage()) . "');
            alert('錯誤：" . addslashes($e->getMessage()) . "');
            history.back();
        </script>";
    }
}
?>