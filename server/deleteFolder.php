<?php
	include 'db_conn.php';
	
	$fid = $_POST['folder-id'] ? $_POST['folder-id']:null;

	$sql = "DELETE FROM folders WHERE idfolders = ?";
	$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
	mysqli_stmt_bind_param($stmt, "d", $fid) or die(mysqli_error($conn));
	mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
	mysqli_stmt_close($stmt);

	$sql2 = "SELECT * FROM inbox WHERE folder = $fid";
	$r2 = mysqli_query($conn, $sql2) or die(mysqli_error($conn));
	while($row = mysqli_fetch_assoc($r2)){
		$mid = $row['idinbox'];
		$turn = 1;

		$sql3 = "UPDATE inbox SET folder = ? WHERE idinbox = ?";
		$stmt = mysqli_prepare($conn, $sql3) or die(mysqli_error($conn));
		mysqli_stmt_bind_param($stmt, "dd", $turn, $mid) or die(mysqli_error($conn));
		mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
		mysqli_stmt_close($stmt);
	}

	echo "Message moved to inbox, and folder deleted!";

	mysqli_close($conn);
?>