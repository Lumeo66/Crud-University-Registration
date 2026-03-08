<?php
$host     = "sql123.infinityfree.com"; // from your panel
$user     = "if0_41333713";
$password = "8Weg5J3AeFBE";
$database = "if0_41333713_db_unniversityregistration";

$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) die("Connection failed: " . mysqli_connect_error());
?>
```