<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_conn.php';
ini_set("session.save_path", "/home/unn_w13020720/sessionData");
session_start();


$id = $_GET['id'];
$one = 1;
$today = date('Y-m-d');

$sql= "UPDATE advert SET active = ? WHERE idadvert = ? ";
$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
mysqli_stmt_bind_param($stmt, "dd", $one, $id) or die(mysqli_error($conn));
mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
mysqli_stmt_close($stmt);


header('Location: ../myAdvert.php');
exit
?>