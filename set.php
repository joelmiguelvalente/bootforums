<?php
session_start();
$_SESSION['username'] = "mitchell";
header("Location: ./");
die();

?>