<?php
require_once("../ceramics_db_connect.php");

$id=$_GET["id"];
$sql="UPDATE instructor SET deleted_at = NOW(), valid=0 WHERE id=$id";
$result=$conn->query($sql);

header("Location: instructors.php");