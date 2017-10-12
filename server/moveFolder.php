<?php
	include 'db_conn.php';
	
	$mid = $_POST['message-id'] ? $_POST['message-id']:null;
	$fid = $_POST['folder-id'] ? $_POST['folder-id']:null;

	$sql = "UPDATE inbox SET folder = ? WHERE idinbox = ?";
	$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
	mysqli_stmt_bind_param($stmt, "dd", $fid, $mid) or die(mysqli_error($conn));
	mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
	mysqli_stmt_close($stmt);

	echo "Message moved to folder!";

	mysqli_close($conn);
?>