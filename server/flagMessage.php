<?php
	include 'db_conn.php';
	
	$mid = $_POST['message-id'] ? $_POST['message-id']:null;
	$flag = 1;

	$sql = "UPDATE inbox SET flag = ? WHERE idinbox = ?";
	$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
	mysqli_stmt_bind_param($stmt, "dd", $flag, $mid) or die(mysqli_error($conn));
	mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
	mysqli_stmt_close($stmt);
	
	echo "Message has been flagged for review. A member of staff will look at it immediately.";

	mysqli_close($conn);
?>