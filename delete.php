<?php include('config.php');
$id = (int)$_GET['id'];
mysqli_query($conn, "DELETE FROM university_students WHERE id=$id");
header("Location: index.php?msg=Student removed from registry.");
exit;
?>