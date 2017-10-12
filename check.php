<?php
	include 'server/db_conn.php';
	$newID = 1;

	$sqlCheck = "SELECT * FROM inbox WHERE idinbox = 1";
	$r = mysqli_query($conn, $sqlCheck) or die(mysqli_error($conn));

	while($row = mysqli_fetch_assoc($r)){
		$id = $row['idinbox'];
		$subCheck = $row['subject'];
		$bodCheck = $row['body'];
		$dateCheck = $row['senddate'];
		$timeCheck = $row['sendtime'];
		$readCheck = $row['readCheck'];

    	echo "<p>$id</p>
       	<p>$subCheck</p>
       	<p>$bodCheck</p>
       	<p>$dateCheck</p>
       	<p>$timeCheck</p>
       	<p>$readCheck</p>";
	}
?>