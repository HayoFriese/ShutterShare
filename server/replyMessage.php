<?php
	include 'db_conn.php';

	$uid = $_POST['message_id'] ? $_POST['message_id']:null;
	$subject = $_POST['subject_reply'] ? $_POST['subject_reply']:null;
	$to = $_POST['to_reply'] ? $_POST['to_reply']:null;
	$body = $_POST['body'] ? $_POST['body']:null;
	$from = $_POST['from_reply'] ? $_POST['from_reply']:null;

	$sendDate = date("Y-m-d");
	$sendTime = date("H:i:s");
	$readMark = 0;
	$flag = 0;
	$sent = 1;
	$folder = 1;

	$draftchecksent = 0;

	$touser = "";

	$sqlUser = "SELECT iduser FROM shutuser WHERE username = '$to'";
	$r = mysqli_query($conn, $sqlUser) or die(mysqli_error($conn));
	if(mysqli_num_rows($r) == 1){
		$row = mysqli_fetch_assoc($r);
		$touser = $row['iduser'];

		$sqlTest = "SELECT * FROM inbox 
			WHERE touser = $touser AND fromuser = $from AND readMark = $sent AND sent = $draftchecksent AND folder = $folder AND flag = $flag AND subject = '$subject'";
			$rTest = mysqli_query($conn, $sqlTest) or die(mysqli_error($conn));

		if(mysqli_num_rows($rTest) > 0){
			$sqlMakeSpace = "DELETE FROM inbox WHERE touser = ? AND fromuser = ? AND readMark = ? AND sent = ? AND folder = ? AND flag = ? AND subject = ?";
			$stmt = mysqli_prepare($conn, $sqlMakeSpace) or die(mysqli_error($conn));
			mysqli_stmt_bind_param($stmt, "dddddds", $touser, $from, $sent, $draftchecksent, $folder, $flag, $subject) or die(mysqli_error($conn));
			mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
			mysqli_stmt_close($stmt) or die(mysqli_error($conn)); 
			
			$sql = "INSERT INTO inbox(subject, body, senddate, sendtime, readMark, flag, sent, touser, fromuser, folder) 
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
			mysqli_stmt_bind_param($stmt, "ssssssssss", $subject, $body, $sendDate, $sendTime, $readMark, $flag, $sent, $touser, $from, $folder) or die(mysqli_error($conn));
			mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
			mysqli_stmt_close($stmt) or die(mysqli_error($conn));
			
			echo "Reply has been overwritten";
		
		} elseif(mysqli_num_rows($rTest) == 0) {
			$sql = "INSERT INTO inbox(subject, body, senddate, sendtime, readMark, flag, sent, touser, fromuser, folder) 
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
			mysqli_stmt_bind_param($stmt, "ssssssssss", $subject, $body, $sendDate, $sendTime, $readMark, $flag, $sent, $touser, $from, $folder) or die(mysqli_error($conn));
			mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
			mysqli_stmt_close($stmt) or die(mysqli_error($conn));
			echo "Reply has been sent!";
		}
		$replyID = mysqli_insert_id($conn);
		
		$images = json_decode($_POST['image'], true);
		
		if(isset($_FILES['image-reply'])){
			foreach($_FILES['image-reply']['name'] as $f => $name){
				$movedir = ("../resources/img/inbox/".$replyID."/");
	    		$dir = ("resources/img/inbox/".$replyID."/");
	
				$allowedExts = array("gif", "jpeg", "jpg", "png", "PNG", "JPG", "JPEG", "GIF");
				$temp = explode(".", $name);
				$extension = end($temp);
				//Set file type and size
				if ((($_FILES['image-reply']['type'][$f] == "image/gif") || ($_FILES['image-reply']['type'][$f] == "image/GIF") 
				|| ($_FILES['image-reply']['type'][$f] == "image/jpeg") || ($_FILES['image-reply']['type'][$f] == "image/JPEG") 
				|| ($_FILES['image-reply']['type'][$f] == "image/jpg") || ($_FILES['image-reply']['type'][$f] == "image/JPG") 
				|| ($_FILES['image-reply']['type'][$f] == "image/png") || ($_FILES['image-reply']['type'][$f] == "image/PNG"))
				&& ($_FILES['image-reply']['size'][$f] < 1073741824)
				&& in_array($extension, $allowedExts)){
					echo "true";
					if($_FILES['image-reply']['error'][$f] > 0){
						echo "Return Code: ".$_FILES['image-reply']['error'][$f]."<br />";
					
					} else {
						if(file_exists($movedir)){
							echo "Directory Exists";
						} else {
							mkdir($movedir, 0777, true);
						}
						if(file_exists($movedir.$name)){
							echo "File already exists";
							$pathname = $dir.$name;
						} else {
							$names = $_FILES['image-reply']['tmp_name'][$f];
							if(move_uploaded_file($names, "$movedir/$name")){
								$pathname = ($dir."/".$name);
								echo "File has been uploaded";
							
							}
						}
					}
				}	
			}
		}

		if(isset($_FILES['file-reply'])){
			$zipArray = [];
			$zipName = ("../resources/img/inbox/".$replyID."/".$replyID."-".$to."-archive.zip");
			$zipDir = ("../resources/img/inbox/".$replyID."/");
			$zipTable = ("resources/img/inbox/".$replyID."/".$replyID."-".$to."-archive.zip");

			foreach($_FILES['file-reply']['name'] as $f => $name){
	
				//Set file types
				//$allowedExts = array("gif", "jpeg", "jpg", "png", "PNG", "JPG", "JPEG", "GIF");
				//$temp = explode(".", $name);
				//$extension = end($temp);	

				if (($_FILES['file-reply']['size'][$f] < 1073741824) /*&& in_array($extension, $allowedExts*/){

					if($_FILES['file-reply']['error'][$f] > 0){
						echo "Return Code: ".$_FILES['file-reply']['error'][$f]."<br />";
					
					} else {
						if(file_exists($zipDir)){
							echo "Directory Exists";
						} else {
							mkdir($zipDir, 0777, true);
						}
						if(file_exists($zipName)){
							echo "File already exists";
							$zip = new ZipArchive();
							if($zip->open($zipName) === TRUE){
								$zip->addFile($_FILES['file-reply']['tmp_name'][$f], $_FILES['file-reply']['name'][$f]);
								$zip->close();
								echo " File added to Zip";
							}
						} else {
							$names = $_FILES['file-reply']['tmp_name'][$f];
							$zip = new ZipArchive();
							$zip->open($zipName, ZipArchive::CREATE);

							$zip->addFile($_FILES['file-reply']['tmp_name'][$f], $_FILES['file-reply']['name'][$f]);
							
							$zip->close();
						}
					}
				}	
			}
			$sqlAttach = "UPDATE inbox SET file = ? WHERE idinbox = ?";
				$stmt = mysqli_prepare($conn, $sqlAttach) or die(mysqli_error($conn));
				mysqli_stmt_bind_param($stmt, "ss", $zipTable, $replyID) or die(mysqli_error($conn));
				mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
				mysqli_stmt_close($stmt) or die(mysqli_error($conn));
				echo "File has been uploaded";
		}
		
	} else{
		die(mysqli_error($conn));
	}

	mysqli_close($conn);
?>