<?php
session_start();

$_SESSION['status'] = "You logged out successfully.";
header("Location: login.php");

?>