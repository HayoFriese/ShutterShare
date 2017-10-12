<?php
	include 'db_conn.php';

	if(isset($_POST['submit'])){
		$foldername = isset($_POST['folder-name']) ? $_POST['folder-name']:null;
		$uid = $_POST['userid'] ? $_POST['userid']:null;

		$sql = "INSERT INTO folders(folder, user) VALUES(?, ?)";
		$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
		mysqli_stmt_bind_param($stmt, "sd", $foldername, $uid) or die(mysqli_error($conn));
		mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
		mysqli_stmt_close($stmt);
	} else {
		die(mysqli_error($conn));
	}
	mysqli_close($conn);

	header("location: ../inbox.php");
?>