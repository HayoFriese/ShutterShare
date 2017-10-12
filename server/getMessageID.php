<?php
	include "db_conn.php";
	$sql = "SELECT idinbox FROM inbox ORDER BY idinbox DESC LIMIT 1";
	$r = mysqli_query($conn, $sql) or die(mysqli_error($conn));
	if(mysqli_num_rows($r) == 1){
		$row = mysqli_fetch_assoc($r);
		echo $row['idinbox'];
	}
?>