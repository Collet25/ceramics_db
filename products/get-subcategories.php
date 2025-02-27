<?php
require_once("../ceramics_db_connect.php");

if(isset($_POST["category"])){
    $category = $_POST["category"];
    $selected = isset($_POST["selected"]) ? $_POST["selected"] : "";
    
    $sql = "SELECT s.* FROM subcategories s 
            JOIN categories c ON s.category_id = c.id 
            WHERE c.name = ?
            ORDER BY s.name";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo '<option value="">所有子分類</option>';
    while($row = $result->fetch_assoc()){
        $isSelected = $row["name"] === $selected ? "selected" : "";
        echo '<option value="'.$row["name"].'" '.$isSelected.'>'.$row["name"].'</option>';
    }
}
?>