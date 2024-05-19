<?php
session_start();
$_SESSION['username'] = $_POST['username'];
$_SESSION['guest'] = true;
header("Location: dashboard.php");
exit();
?>
