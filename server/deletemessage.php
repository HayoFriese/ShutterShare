<?php
	include "db_conn.php";

	$uid = $_POST['message-id'] ? $_POST['message-id']:null;
	$zero = 0;

	$sql = "UPDATE inbox SET folder = ? WHERE idinbox = ?";
	$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
	mysqli_stmt_bind_param($stmt, "dd", $zero, $uid) or die(mysqli_error($conn));
	mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
	mysqli_stmt_close($stmt);
?>