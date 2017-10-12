<?php
	include 'db_conn.php';
	
	if(isset($_POST['draftbool']) && $_POST['draftbool'] == 1){
		$idofdraft = $_POST['idmessage'] ? $_POST['idmessage']:null;
		$subject = $_POST['subject_new'] ? $_POST['subject_new']:null;
		$to = $_POST['to_new'];
		$body = $_POST['body'];
		$from = $_POST['from_new'] ? $_POST['from_new']:null;

		$sendDate = date("Y-m-d");
		$sendTime = date("H:i:s");
		$readMark = 1;
		$flag = 0;
		$sent = 0;
		$folder = 1;

		$touser = "";

		$sqlUser = "SELECT iduser FROM shutuser WHERE username = '$to'";
		$r = mysqli_query($conn, $sqlUser) or die(mysqli_error($conn));
		if(mysqli_num_rows($r) == 1){
			$row = mysqli_fetch_assoc($r);
			$touser = $row['iduser'];

			$sqlNewMessage = "UPDATE inbox SET subject = ?, 
			body = ?, senddate = ?, sendtime = ?, readMark = ?, flag = ?, 
			sent = ?, touser = ?, fromuser = ?, folder = ? 
			WHERE idinbox = ?";
			$stmt = mysqli_prepare($conn, $sqlNewMessage) or die(mysqli_error($conn));
			mysqli_stmt_bind_param($stmt, "ssssssssssd", $subject, $body, $sendDate, $sendTime, $readMark, $flag, $sent, $touser, $from, $folder, $idofdraft) or die(mysqli_error($conn));
			mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
			mysqli_stmt_close($stmt) or die(mysqli_error($conn));
		
			echo "Draft Has Been Saved!";
		} else{
			die(mysqli_error($conn));
		}
	}

	elseif(isset($_POST['draftbool']) && $_POST['draftbool'] == 2){
		$idofdraft = $_POST['message_id'] ? $_POST['message_id']:null;
		$subject = $_POST['subject_reply'];
		$to = $_POST['to_reply'];
		$body = $_POST['body'];
		$from = $_POST['from_reply'] ? $_POST['from_reply']:null;

		$sendDate = date("Y-m-d");
		$sendTime = date("H:i:s");
		$readMark = 1;
		$flag = 0;
		$sent = 0;
		$folder = 1;

		$touser = "";

		$sqlUser = "SELECT iduser FROM shutuser WHERE username = '$to'";
		$r = mysqli_query($conn, $sqlUser) or die(mysqli_error($conn));
		if(mysqli_num_rows($r) == 1){
			$row = mysqli_fetch_assoc($r);
			$touser = $row['iduser'];

			$sqlTest = "SELECT * FROM inbox WHERE touser = $touser AND fromuser = $from AND readMark = $readMark AND sent = $sent AND folder = $folder AND flag = $flag";
			$rTest = mysqli_query($conn, $sqlTest) or die(mysqli_error($conn));
			
			if(mysqli_num_rows($rTest) > 0){
				$sqlNewMessage = "UPDATE inbox SET subject = ?, body = ?, senddate = ?, sendtime = ?, readMark = ?, flag = ?, sent = ?, touser = ?, fromuser = ?, folder = ? 
				WHERE subject = ? AND touser = ? AND fromuser = ? AND readMark = ? AND sent = ? AND folder = ? AND flag = ?";
				$stmt = mysqli_prepare($conn, $sqlNewMessage) or die(mysqli_error($conn));
				mysqli_stmt_bind_param($stmt, "sssssssssssdddddd", $subject, $body, $sendDate, $sendTime, $readMark, $flag, $sent, $touser, $from, $folder, 
					$subject, $touser, $from, $readMark, $sent, $folder, $flag) or die(mysqli_error($conn));
				mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
				mysqli_stmt_close($stmt) or die(mysqli_error($conn));

			} elseif(mysqli_num_rows($rTest) == 0) {
				$sql = "INSERT INTO inbox(subject, body, senddate, sendtime, readMark, flag, sent, touser, fromuser, folder) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
				mysqli_stmt_bind_param($stmt, "ssssssssss", $subject, $body, $sendDate, $sendTime, $readMark, $flag, $sent, $touser, $from, $folder) or die(mysqli_error($conn));
				mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
				mysqli_stmt_close($stmt) or die(mysqli_error($conn));
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
						if($_FILES['image-reply']['error'][$f] > 0){
							echo "Return Code: ".$_FILES['image-reply']['error'][$f]."<br />";
						
						} else {
							if(file_exists($movedir)){
								echo " Directory Exists";
							} else {
								mkdir($movedir, 0777, true);
							}
							if(file_exists($movedir.$name)){
								$pathname = $dir.$name;
							} else {
								$names = $_FILES['image-reply']['tmp_name'][$f];
								if(move_uploaded_file($names, "$movedir/$name")){
									$pathname = ($dir."/".$name);								
								}
							}
						}
					}
				}
			}
			echo $replyID;
		} else{
			die(mysqli_error($conn));
		}
	} elseif(isset($_POST['draftbool']) && $_POST['draftbool'] == 4){
		$idofdraft = $_POST['id_mess'] ? $_POST['id_mess']:null;
		$body = $_POST['body'];

		$sqlLastSetp = "UPDATE inbox SET body = ? WHERE idinbox = ?";
		$stmt = mysqli_prepare($conn, $sqlLastSetp) or die(mysqli_error($conn));
		mysqli_stmt_bind_param($stmt, "sd", $body, $idofdraft) or die(mysqli_error($conn));
		mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
		mysqli_stmt_close($stmt) or die(mysqli_error($conn));

		echo "Message draft has been saved";
	}

	elseif(isset($_POST['draftbool']) && $_POST['draftbool'] == 3){
		$subject = $_POST['subject_new'];
		$to = $_POST['to_new'];
		$body = $_POST['body'];
		$from = $_POST['from_new'] ? $_POST['from_new']:null;

		$sendDate = date("Y-m-d");
		$sendTime = date("H:i:s");
		$readMark = 1;
		$flag = 0;
		$sent = 0;
		$folder = 1;

		$touser = "";

		$sqlUser = "SELECT iduser FROM shutuser WHERE username = '$to'";
		$r = mysqli_query($conn, $sqlUser) or die(mysqli_error($conn));
		if(mysqli_num_rows($r) == 1){
			$row = mysqli_fetch_assoc($r);
			$touser = $row['iduser'];

			$sqlTest = "SELECT * FROM inbox WHERE touser = $touser AND fromuser = $from AND readMark = $readMark AND sent = $sent AND folder = $folder AND flag = $flag";
			$rTest = mysqli_query($conn, $sqlTest) or die(mysqli_error($conn));
			
			if(mysqli_num_rows($rTest) > 0){
				$sqlNewMessage = "UPDATE inbox SET subject = ?, body = ?, senddate = ?, sendtime = ?, readMark = ?, flag = ?, sent = ?, touser = ?, fromuser = ?, folder = ? 
				WHERE subject = ? AND touser = ? AND fromuser = ? AND readMark = ? AND sent = ? AND folder = ? AND flag = ?";
				$stmt = mysqli_prepare($conn, $sqlNewMessage) or die(mysqli_error($conn));
				mysqli_stmt_bind_param($stmt, "sssssssssssdddddd", $subject, $body, $sendDate, $sendTime, $readMark, $flag, $sent, $touser, $from, $folder, 
					$subject, $touser, $from, $readMark, $sent, $folder, $flag) or die(mysqli_error($conn));
				mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
				mysqli_stmt_close($stmt) or die(mysqli_error($conn));

			} elseif(mysqli_num_rows($rTest) == 0) {
				$sql = "INSERT INTO inbox(subject, body, senddate, sendtime, readMark, flag, sent, touser, fromuser, folder) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
				mysqli_stmt_bind_param($stmt, "ssssssssss", $subject, $body, $sendDate, $sendTime, $readMark, $flag, $sent, $touser, $from, $folder) or die(mysqli_error($conn));
				mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
				mysqli_stmt_close($stmt) or die(mysqli_error($conn));

			}
				
			$newID = mysqli_insert_id($conn);
		
			$images = json_decode($_POST['image'], true);
		
			if(isset($_FILES['image-new'])){
				foreach($_FILES['image-new']['name'] as $f => $name){
					$movedir = ("../resources/img/inbox/".$newID."/");
	    			$dir = ("resources/img/inbox/".$newID."/");
	
					$allowedExts = array("gif", "jpeg", "jpg", "png", "PNG", "JPG", "JPEG", "GIF");
					$temp = explode(".", $name);
					$extension = end($temp);
					//Set file type and size
					if ((($_FILES['image-new']['type'][$f] == "image/gif") || ($_FILES['image-new']['type'][$f] == "image/GIF") 
					|| ($_FILES['image-new']['type'][$f] == "image/jpeg") || ($_FILES['image-new']['type'][$f] == "image/JPEG") 
					|| ($_FILES['image-new']['type'][$f] == "image/jpg") || ($_FILES['image-new']['type'][$f] == "image/JPG") 
					|| ($_FILES['image-new']['type'][$f] == "image/png") || ($_FILES['image-new']['type'][$f] == "image/PNG"))
					&& ($_FILES['image-new']['size'][$f] < 1073741824)
					&& in_array($extension, $allowedExts)){
						if($_FILES['image-new']['error'][$f] > 0){
							echo "There was an error uploading the file. Return Code: ".$_FILES['image-new']['error'][$f]."<br />";
						
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
		} else{
			die(mysqli_error($conn));
		}
	}
	
	else{
		$idofdraft = $_POST['message_id'] ? $_POST['message_id']:null;
		$subject = $_POST['subject_draft'] ? $_POST['subject_draft']:null;
		$to = $_POST['to_draft'] ? $_POST['to_draft']:null;
		$body = $_POST['body'] ? $_POST['body']:null;;
		$from = $_POST['from_draft'] ? $_POST['from_draft']:null;

		$sendDate = date("Y-m-d");
		$sendTime = date("H:i:s");
		$readMark = 0;
		$flag = 0;
		$sent = 1;
		$folder = 1;

		$touser = "";

		$sqlUser = "SELECT iduser FROM shutuser WHERE username = '$to'";
		$r = mysqli_query($conn, $sqlUser) or die(mysqli_error($conn));
		if(mysqli_num_rows($r) == 1){
			$row = mysqli_fetch_assoc($r);
			$touser = $row['iduser'];

			$sqlNewMessage = "UPDATE inbox SET subject = ?, 
			body = ?, senddate = ?, sendtime = ?, readMark = ?, flag = ?, 
			sent = ?, touser = ?, fromuser = ?, folder = ? 
			WHERE idinbox = ?";
			$stmt = mysqli_prepare($conn, $sqlNewMessage) or die(mysqli_error($conn));
			mysqli_stmt_bind_param($stmt, "ssssssssssd", $subject, $body, $sendDate, $sendTime, $readMark, $flag, $sent, $touser, $from, $folder, $idofdraft) or die(mysqli_error($conn));
			mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
			mysqli_stmt_close($stmt) or die(mysqli_error($conn));
		
			echo "Message has been sent!";
		} else{
			die(mysqli_error($conn));
		}
	}

	mysqli_close($conn);
?>