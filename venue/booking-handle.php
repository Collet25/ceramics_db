<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php
require_once("../ceramics_db_connect.php");

$venue_id = $_POST['venue_id'];
$user_id = $_POST['user_id'];
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];

$sql = "INSERT INTO bookings (venue_id, user_id, start_time, end_time) 
        VALUES ('$venue_id', '$user_id', '$start_time', '$end_time')";

if ($conn->query($sql) === TRUE) {
    header("Location: booking-list.php");
} else {
    echo "錯誤: " . $sql . "<br>" . $conn->error;
}
?>
