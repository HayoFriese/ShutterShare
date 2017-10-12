<?php
include "db_conn.php";
require_once('../functions.php');

$id = $_POST['ad_id'];
$medid = $_POST['ad_media'];

$sql1 = "SELECT src FROM shutmedia WHERE idmedia = $medid";
$r1 = mysqli_query($conn, $sql1) or die(mysqli_error($conn));
$img = "../".mysqli_fetch_assoc($r1)['src'];
echo $img;

unlink($img);

$sql = "DELETE FROM shutmedia WHERE idmedia = $medid";

$result = mysqli_query($conn, $sql)
or die(mysqli_error($conn));

mysqli_close($conn);
header('Location: ../editAdvert.php?id='.$id);
exit;