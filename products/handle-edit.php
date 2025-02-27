<?php
require_once("../ceramics_db_connect.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    try {
        if(!isset($_POST["id"])){
            throw new Exception("缺少商品ID");
        }

        // 檢查必填欄位
        $required_fields = ["name", "category", "subcategory", "price", "description", "material", "origin"];
        foreach($required_fields as $field){
            if(empty($_POST[$field])){
                throw new Exception("請填寫所有必填欄位");
            }
        }

        // 處理圖片上傳
        $image_name = $_POST["old_image"];
        if(isset($_FILES["image"]) && $_FILES["image"]["error"] === 0){
            $target_dir = "uploads/";
            
            $image_info = getimagesize($_FILES["image"]["tmp_name"]);
            if($image_info === false){
                throw new Exception("請上傳有效的圖片檔案");
            }

            $allowed_types = ["image/jpeg", "image/png", "image/gif"];
            if(!in_array($image_info["mime"], $allowed_types)){
                throw new Exception("只允許上傳 JPG, PNG 或 GIF 格式的圖片");
            }

            $image_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
            $image_name = uniqid() . "." . $image_extension;
            $target_file = $target_dir . $image_name;

            if(!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){
                throw new Exception("圖片上傳失敗");
            }

            // 刪除舊圖片
            if(file_exists($target_dir . $_POST["old_image"])){
                unlink($target_dir . $_POST["old_image"]);
            }
        }

        // 更新商品資料
        $sql = "UPDATE products SET 
                name=?, category=?, subcategory=?, price=?, 
                description=?, image=?, material=?, origin=? 
                WHERE id=?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssissssi", 
            $_POST["name"],
            $_POST["category"],
            $_POST["subcategory"],
            $_POST["price"],
            $_POST["description"],
            $image_name,
            $_POST["material"],
            $_POST["origin"],
            $_POST["id"]
        );

        if(!$stmt->execute()){
            throw new Exception("商品更新失敗");
        }

        echo "<script>
            alert('商品更新成功！');
            location.href = 'product-list.php';
        </script>";

    } catch (Exception $e) {
        echo "<script>
            alert('錯誤：" . $e->getMessage() . "');
            history.back();
        </script>";
    }
}
?>