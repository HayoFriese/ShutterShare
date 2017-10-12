<?php
include "db_conn.php";
require_once('../functions.php');

echo pageIni("Helpful - Shuttershare");
$adid = $_GET['ad'];
$revid = $_GET['id'];

echo $adid;
echo $revid;

$sql = "UPDATE reviews SET helpful = helpful + 1 WHERE idreviews = ?";
//prepared statement
$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));

mysqli_stmt_bind_param($stmt, 'd', $revid) or die(mysqli_error($conn));

mysqli_stmt_execute($stmt) or die(mysqli_error($conn));

mysqli_stmt_close($stmt);
//on complete,
mysqli_close($conn);
header('Location: ../viewAdvert.php?id='.$adid);
echo pageClose();
