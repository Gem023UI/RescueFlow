<?php
session_start();

if (!isset($_SESSION['uname'])) {
    include('./includes/header.php');
}
include('./includes/config.php');
?>
low