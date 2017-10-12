<?php
	include 'db_conn.php';
	
	$mid = $_POST['message-id'] ? $_POST['message-id']:null;
	$flag = 0;

	$sql = "UPDATE inbox SET flag = ? WHERE idinbox = ?";
	$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
	mysqli_stmt_bind_param($stmt, "dd", $flag, $mid) or die(mysqli_error($conn));
	mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
	mysqli_stmt_close($stmt);
	
	echo "Message has been unflagged.";

	mysqli_close($conn);
?>