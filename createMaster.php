<?php
	include "server/db_conn.php";
?>
<html>
<head></head>
<body>
	<?php
		if(isset($_POST['submit'])){
			$changekey = filter_has_var(INPUT_POST, 'changekey') ? $_POST['changekey'] : null;
			$userkey = filter_has_var(INPUT_POST, 'userkey') ? $_POST['userkey'] : null;

			$pass1 = filter_has_var(INPUT_POST, 'pass1') ? $_POST['pass1'] : null;
			$pass1 = trim($pass1);
	        $pass1 = filter_var($pass1, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
			$passhash = password_hash($pass1, PASSWORD_DEFAULT);

			$pass2 = filter_has_var(INPUT_POST, 'pass2') ? $_POST['pass2'] : null;
			$pass2 = trim($pass2);
	        $pass2 = filter_var($pass2, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
			$passhash2 = password_hash($pass2, PASSWORD_DEFAULT);


			$master = 1;
			$fname = "Hayo";
			$lname = "Friese";
			$birthday = date("Y-m-d", strtotime("1994-08-20"));
			$email = "hayo.friese@gmail.com";
			$created = date("Y-m-d H:i:s");

			$sql = "INSERT INTO admin(userkey, password, secondpass, changekey, master, firstname, lastname, email, created) 
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
    		mysqli_stmt_bind_param($stmt, "ssssdssss", $userkey, $passhash, $passhash2, $changekey, $master, $fname, $lname, $email, $created) or die(mysqli_error($conn));
    		mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
    		mysqli_stmt_close($stmt);

    		echo "done";
		} else {
			echo "<form action=\"createMaster.php\" method=\"post\">
		<input type=\"text\" name=\"userkey\">
		<input type=\"text\" name=\"pass1\">
		<input type=\"text\" name=\"pass2\">
		<input type=\"text\" name=\"changekey\">

		<input type=\"submit\" name=\"submit\">
	</form>";
		}
	?>
	
</body>
</html>