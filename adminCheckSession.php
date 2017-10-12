<?php
if (!isset($_SESSION['logged-in']) && $_SESSION['logged-in'] != true) {
    header("location: admin_login.php");
    die;
}
?>