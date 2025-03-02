<?php
require_once("../ceramics_db_connect.php");

$id=$_GET["id"];
$sql="UPDATE instructor SET deleted_at = NULL, valid=1 WHERE id=$id";
$result=$conn->query($sql);

header("Location: delete-instructors.php");