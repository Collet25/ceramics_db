<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php
require_once("../ceramics_db_connect.php");

if(!isset($_POST["account"])){
    die("請循正常管道進入此頁");
}

$account=$_POST["account"];
$password=$_POST["password"];
$name=$_POST["name"];
$email=$_POST["email"];
$phone=$_POST["phone"];
$gender=$_POST["gender"];
$birth = date("Y-m-d", strtotime($_POST["birth"]));
$frozen = $_POST["frozen"];
$now=date("Y-m-d H:i:s");

// $repassword = $_POST["repassword"];

$sql = "SELECT * FROM users WHERE account='$account'";
$result = $conn->query($sql);
$userCount = $result->num_rows;

// echo $userCount;

if ($userCount > 0) {
    // 帳號已存在，回傳錯誤訊息
    header("location: sign-up.php?error=該帳號已註冊");
    exit();
}


$sql="INSERT INTO users (account, password, name, email, phone, gender, birth, created_at, valid, frozen) VALUES ('$account', '$password', '$name', '$email', '$phone', '$gender', '$birth', '$now', '0', ' ')";
// echo $sql;

// if ($conn->query($sql) === TRUE) {
//     $last_id = $conn->insert_id;
//     echo "新資料輸入成功, id 為 $last_id";
// } else {
//     echo "Error: " . $sql . "<br>" . $conn->error;
// }
// $conn->close();
// header("location: sign-up.php");

if ($conn->query($sql) === TRUE) {
    // 註冊成功，跳轉到登入頁面
    header("location: users.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
    exit();
}
?>
