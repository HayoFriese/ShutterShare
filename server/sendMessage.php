<?php
	include 'db_conn.php';

	$subject = $_POST['subject_new'] ? $_POST['subject_new']:null;
	$to = $_POST['to_new'] ? $_POST['to_new']:null;
	$body = $_POST['body'] ? $_POST['body']:null;
	$from = $_POST['from_new'] ? $_POST['from_new']:null;

	$sendDate = date("Y-m-d");
	$sendTime = date("H:i:s");
	$readMark = 0;
	$flag = 0;
	$sent = 1;
	$draftchecksent = 0;
	$folder = 1;

	$touser = "";

	$sqlUser = "SELECT iduser FROM shutuser WHERE username = '$to'";
	$r = mysqli_query($conn, $sqlUser) or die(mysqli_error($conn));
	if(mysqli_num_rows($r) == 1){
		$row = mysqli_fetch_assoc($r);
		$touser = $row['iduser'];

		$sqlNewMessage = "INSERT INTO inbox(subject, body, senddate, sendtime, readMark, flag, sent, touser, fromuser, folder) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = mysqli_prepare($conn, $sqlNewMessage) or die(mysqli_error($conn));
		mysqli_stmt_bind_param($stmt, "ssssssssss", $subject, $body, $sendDate, $sendTime, $readMark, $flag, $sent, $touser, $from, $folder) or die(mysqli_error($conn));
		mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
		mysqli_stmt_close($stmt) or die(mysqli_error($conn));
	
		echo "Message has been sent!";

		$newID = mysqli_insert_id($conn);
		//run a counter
		$images = json_decode($_POST['image'], true);

		if(isset($_FILES['image-new']) && $_FILES['image-new']['error'][0] == 0){
			foreach($_FILES['image-new']['name'] as $f => $name){
				$movedir = ("../resources/img/inbox/".$newID."/");
	   	 		$dir = ("resources/img/inbox/".$newID."/");
				
				$allowedExts = array("gif", "jpeg", "jpg", "png", "PNG", "JPG", "JPEG", "GIF");
				$temp = explode(".", $name);
				$extension = end($temp);

				if ((($_FILES['image-new']['type'][$f] == "image/gif") || ($_FILES['image-new']['type'][$f] == "image/GIF") 
				|| ($_FILES['image-new']['type'][$f] == "image/jpeg") || ($_FILES['image-new']['type'][$f] == "image/JPEG") 
				|| ($_FILES['image-new']['type'][$f] == "image/jpg") || ($_FILES['image-new']['type'][$f] == "image/JPG") 
				|| ($_FILES['image-new']['type'][$f] == "image/png") || ($_FILES['image-new']['type'][$f] == "image/PNG"))
				&& ($_FILES['image-new']['size'][$f] < 1073741824)
				&& in_array($extension, $allowedExts)){
					if($_FILES['image-new']['error'][$f] > 0){
						echo "There was a problem uploading your in-body images. Return Code: ".$_FILES['image-new']['error'][$f]."<br />";
					
					} else {
						if(file_exists($movedir)){
						} else {
							mkdir($movedir, 0777, true);
						}
						if(file_exists($movedir.$name)){
							$pathname = $dir.$name;
						} else {
							$names = $_FILES['image-new']['tmp_name'][$f];
							if(move_uploaded_file($names, "$movedir/$name")){
								$pathname = ($dir."/".$name);							
							}
						}
					}
				}
	   	 			
			}
		}

		if(isset($_FILES['file-new']) && $_FILES['file-new']['error'][0] == 0){
			$zipArray = [];
			$zipName = ("../resources/img/inbox/".$newID."/".$newID."-".$to."-archive.zip");
			$zipDir = ("../resources/img/inbox/".$newID."/");
			$zipTable = ("resources/img/inbox/".$newID."/".$newID."-".$to."-archive.zip");

			foreach($_FILES['file-new']['name'] as $f => $name){

				if (($_FILES['file-new']['size'][$f] < 1073741824) ){

					if($_FILES['file-new']['error'][$f] > 0){
						echo "Return Code: ".$_FILES['file-new']['error'][$f]."<br />";
					
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
								$zip->addFile($_FILES['file-new']['tmp_name'][$f], $_FILES['file-new']['name'][$f]);
								$zip->close();
								echo " File added to Zip";
							}
						} else {
							$names = $_FILES['file-new']['tmp_name'][$f];
							$zip = new ZipArchive();
							$zip->open($zipName, ZipArchive::CREATE);

							$zip->addFile($_FILES['file-new']['tmp_name'][$f], $_FILES['file-new']['name'][$f]);
							
							$zip->close();
						}
						$sqlAttach = "UPDATE inbox SET file = ? WHERE idinbox = ?";
						$stmt = mysqli_prepare($conn, $sqlAttach) or die(mysqli_error($conn));
						mysqli_stmt_bind_param($stmt, "ss", $zipTable, $newID) or die(mysqli_error($conn));
						mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
						mysqli_stmt_close($stmt) or die(mysqli_error($conn));
						echo "File has been uploaded";
					}
				}	
			}

		}
	} else{
		die(mysqli_error($conn));
	}

	mysqli_close($conn);
?>