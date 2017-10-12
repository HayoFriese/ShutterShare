<?php
	$filter = isset($_GET['filter']) ? $_GET['filter']:null;
	if(!$messageid) {
		echo "<div class=\"inbox-empty\">
			<p>select a message</p>
		</div>";
	} elseif(isset($messageid) && $filter == "drafts"){
				$sqlMessage = "SELECT * FROM inbox WHERE idinbox = $messageid AND sent = 0";
				$r = mysqli_query($conn, $sqlMessage) or die(mysqli_error($conn));
				
				while($row = mysqli_fetch_assoc($r)){
					$messageID = $row['idinbox'];
					$subject = $row['subject'];
					$bodyMessage = $row['body'];
					$date = $row['senddate'];
						$date = date("d-m-Y", strtotime($date));
					$time = $row['sendtime'];
						$time = date("H:i", strtotime($time));
					$toid = $row['touser'];
					$to = "";
					if($toid != "0"){
						$sqlnameTo = "SELECT username FROM shutuser WHERE iduser = $toid";
						$toR = mysqli_query($conn, $sqlnameTo) or die(mysqli_error($conn));
						$to = mysqli_fetch_assoc($toR)['username'];
					} else {
						$to = "(no recipient)";
					}

					echo "<div class=\"inbox-new-message\">
							<form id=\"inbox-draft-send\" method=\"post\" action=\"server/replyMessage.php\" enctype=\"multipart/form-data\">
								<input type=\"hidden\" id=\"message_id\" name=\"message_id\" value=\"$messageID\" required>
								<input type=\"hidden\" id=\"from_draft\" name=\"from_draft\" value= \"$userid\" required/>";
								if($toid != 0){
									echo "<input type=\"text\" id=\"to_draft\" name=\"to_draft\" value=\"$to\" placeholder=\"To...\" />";
								} else {
									echo "<input type=\"text\" id=\"to_draft\" name=\"to_draft\" value=\"\" placeholder=\"To...\" />";
								}
								
								echo "<input type=\"text\" id=\"subject_draft\" name=\"subject_draft\" value= \"$subject\" placeholder=\"Subject...\"/>

								<div class=\"text-editor-bar\">
									<div class=\"text-wrapper\">Text Types
										<ul>
								  			<li><a href=\"#\" data-command=\"h1\"><h1>Header 1</h1></a></li>
								  			<li><a href=\"#\" data-command=\"h2\"><h2>Header 2</h2></a></li>
								  			<li><a href=\"#\" data-command=\"h3\"><h3>Header 3</h3></a></li>
								  			<li><a href=\"#\" data-command=\"h4\"><h4>Header 4</h4></a></li>
								  			<li><a href=\"#\" data-command=\"p\"><p>Normal Text</p></a></li>
								  		</ul>
									</div>
									
								  	<div class=\"font-wrapper\"><i class='fa fa-text-height'></i>
								  		<ul>
								  			<li><a href=\"#\" data-command=\"fontSize\" data-value=\"1\"><font size=\"1\">X-Small</font></a></li>
								  			<li><a href=\"#\" data-command=\"fontSize\" data-value=\"2\"><font size=\"1\">Small</font></a></li>
								  			<li><a href=\"#\" data-command=\"fontSize\" data-value=\"3\"><font size=\"3\">Normal</font></a></li>
								  			<li><a href=\"#\" data-command=\"fontSize\" data-value=\"4\"><font size=\"4\">Large</font></a></li>
								  			<li><a href=\"#\" data-command=\"fontSize\" data-value=\"5\"><font size=\"5\">X-Large</font></a></li>
								  			<li><a href=\"#\" data-command=\"fontSize\" data-value=\"6\"><font size=\"6\">XX-Large</font></a></li>
								  			<li><a href=\"#\" data-command=\"fontSize\" data-value=\"7\"><font size=\"7\">Largest</font></a></li>
								  		</ul>
								  	</div>
	
								  	<div class=\"editor-break\"></div>
	
								  	<div class=\"fore-wrapper\"><i class='fa fa-font'></i>
								    	<div class=\"fore-palette\">
								   		</div>
								  	</div>
								  	<a href=\"#\" data-command=\"bold\"><i class=\"fa fa-bold\"></i></a>
								  	<a href=\"#\" data-command=\"italic\"><i class=\"fa fa-italic\"></i></a>
								  	<a href=\"#\" data-command=\"underline\"><i class=\"fa fa-underline\"></i></a>
								  	<a href=\"#\" data-command=\"strikeThrough\"><i class=\"fa fa-strikethrough\"></i></a>
								  	<a href=\"#\" data-command=\"blockquote\"><i class=\"fa fa-quote-right\"></i></a>
	
									<div class=\"editor-break\"></div>
	
								  	<a href=\"#\" data-command=\"justifyLeft\"><i class=\"fa fa-align-left\"></i></a>
								  	<a href=\"#\" data-command=\"justifyCenter\"><i class=\"fa fa-align-center\"></i></a>
								  	<a href=\"#\" data-command=\"justifyRight\"><i class=\"fa fa-align-right\"></i></a>
								  	<a href=\"#\" data-command=\"justifyFull\"><i class=\"fa fa-align-justify\"></i></a>
	
									<div class=\"editor-break\"></div>
	
									<a href=\"#\" data-command=\"insertOrderedList\"><i class=\"fa fa-list-ol\"></i></a>
									<a href=\"#\" data-command=\"insertUnorderedList\"><i class=\"fa fa-list-ul\"></i></a>
								  	<a href=\"#\" data-command=\"indent\"><i class=\"fa fa-indent\"></i></a>
								  	<a href=\"#\" data-command=\"outdent\"><i class=\"fa fa-outdent\"></i></a>
	
								  	<div class=\"editor-break\"></div>
	
								  	<a href=\"#\" data-command=\"insertImage\"><i class=\"fa fa-image\"></i></a>
								  	<a href=\"#\" data-command=\"insertattach\"><i class=\"fa fa-paperclip\"></i></a>
								  	<input type=\"file\" id=\"file-reply\" name=\"file-reply[]\" multiple >
								  	<a href=\"#\" data-command=\"save-draft\"><i class=\"fa fa-floppy-o\"></i></a>
								</div>
								<div id=\"inbox-draft-body\" class=\"draft-content\" contenteditable>
								$bodyMessage
								</div>
								
								<input type=\"submit\" name=\"reply\" value=\"Send\">

								<div class=\"image-list-reply\">
	
								</div> 
								<div class=\"attach-list-reply\" id=\"attach-list-reply\">

								</div>
							</form>
						</div>";
				}
	} elseif(isset($messageid) && $filter == "sent"){
				//$sqlMessage = "SELECT * FROM inbox WHERE idinbox = $messageid";
				$sqlMessSent = "SELECT * FROM inbox WHERE idinbox = $messageid";
				$r = mysqli_query($conn, $sqlMessSent) or die(mysqli_error($conn));
				
				while($row = mysqli_fetch_assoc($r)){
					$messageID = $row['idinbox'];
					$subject = $row['subject'];
					if($subject == ""){
						$subject = "(no subject)";
					}
					$bodyMessage = $row['body'];
					$flag = $row['flag'];
					$date = $row['senddate'];
						$date = date("d-m-Y", strtotime($date));
					$time = $row['sendtime'];
						$time = date("H:i", strtotime($time));
					$fromid = $row['fromuser'];
					$file = $row['file'];
					$to = "";

					$sqlToName = "SELECT username FROM shutuser WHERE iduser = $fromid";
					$rTo = mysqli_query($conn, $sqlToName) or die(mysqli_error($conn));
					$to = mysqli_fetch_assoc($rTo)['username'];

					if($flag == 1){
						echo "<div class=\"message-flagged\"><p>THIS MESSAGE HAS BEEN FLAGGED. IT IS UNDER REVIEW BY A STAFF MEMBER</p></div>";
					}
						echo "<div class=\"inbox-view-message\">
							<header>
								<h2>To <span>$to</span></h2>
								<h3>$date, at $time</h3>";
							if($file){
								echo "<h4><a href=\"$file\">Download Attachment</a></h4>";
							}
							echo "</header>
							<h1>$subject</h1>
							<div class=\"message-body\" id=\"message-body\">
								$bodyMessage
							</div>
						</div>";
				}
	}

	elseif($messageid == "new") {
			echo "<div class=\"inbox-new-message\">
						<form method=\"post\" action=\"sendMessage.php\" id=\"inbox-new\" enctype=\"multipart/form-data\">
							<input type=\"text\" name=\"to_new\" id=\"to_new\" placeholder=\"To...\" autofocus/>
							<input type=\"hidden\" name=\"from_new\" id=\"from_new\" value=\"$userid\"/>
							<input type=\"text\" name=\"subject_new\" id=\"subject_new\" placeholder=\"Subject...\">
							
							<div class=\"text-editor-bar\">
								<div class=\"text-wrapper\">Text Types
									<ul>
							  			<li><a href=\"#\" data-command=\"h1\"><h1>Header 1</h1></a></li>
							  			<li><a href=\"#\" data-command=\"h2\"><h2>Header 2</h2></a></li>
							  			<li><a href=\"#\" data-command=\"h3\"><h3>Header 3</h3></a></li>
							  			<li><a href=\"#\" data-command=\"h4\"><h4>Header 4</h4></a></li>
							  			<li><a href=\"#\" data-command=\"p\"><p>Normal Text</p></a></li>
							  		</ul>
								</div>
								
							  	<div class=\"font-wrapper\"><i class='fa fa-text-height'></i>
							  		<ul>
							  			<li><a href=\"#\" data-command=\"fontSize\" data-value=\"1\"><font size=\"1\">X-Small</font></a></li>
							  			<li><a href=\"#\" data-command=\"fontSize\" data-value=\"2\"><font size=\"1\">Small</font></a></li>
							  			<li><a href=\"#\" data-command=\"fontSize\" data-value=\"3\"><font size=\"3\">Normal</font></a></li>
							  			<li><a href=\"#\" data-command=\"fontSize\" data-value=\"4\"><font size=\"4\">Large</font></a></li>
							  			<li><a href=\"#\" data-command=\"fontSize\" data-value=\"5\"><font size=\"5\">X-Large</font></a></li>
							  			<li><a href=\"#\" data-command=\"fontSize\" data-value=\"6\"><font size=\"6\">XX-Large</font></a></li>
							  			<li><a href=\"#\" data-command=\"fontSize\" data-value=\"7\"><font size=\"7\">Largest</font></a></li>
							  		</ul>
							  	</div>

							  	<div class=\"editor-break\"></div>

							  	<div class=\"fore-wrapper\"><i class='fa fa-font'></i>
							    	<div class=\"fore-palette\">
							   		</div>
							  	</div>
							  	<a href=\"#\" data-command=\"bold\"><i class=\"fa fa-bold\"></i></a>
							  	<a href=\"#\" data-command=\"italic\"><i class=\"fa fa-italic\"></i></a>
							  	<a href=\"#\" data-command=\"underline\"><i class=\"fa fa-underline\"></i></a>
							  	<a href=\"#\" data-command=\"strikeThrough\"><i class=\"fa fa-strikethrough\"></i></a>
							  	<a href=\"#\" data-command=\"blockquote\"><i class=\"fa fa-quote-right\"></i></a>

								<div class=\"editor-break\"></div>

							  	<a href=\"#\" data-command=\"justifyLeft\"><i class=\"fa fa-align-left\"></i></a>
							  	<a href=\"#\" data-command=\"justifyCenter\"><i class=\"fa fa-align-center\"></i></a>
							  	<a href=\"#\" data-command=\"justifyRight\"><i class=\"fa fa-align-right\"></i></a>
							  	<a href=\"#\" data-command=\"justifyFull\"><i class=\"fa fa-align-justify\"></i></a>

								<div class=\"editor-break\"></div>

								<a href=\"#\" data-command=\"insertOrderedList\"><i class=\"fa fa-list-ol\"></i></a>
								<a href=\"#\" data-command=\"insertUnorderedList\"><i class=\"fa fa-list-ul\"></i></a>
							  	<a href=\"#\" data-command=\"indent\"><i class=\"fa fa-indent\"></i></a>
							  	<a href=\"#\" data-command=\"outdent\"><i class=\"fa fa-outdent\"></i></a>

							  	<div class=\"editor-break\"></div>

							  	<a href=\"#\" data-command=\"insertImage\"><i class=\"fa fa-image\"></i></a>
							  	<a href=\"#\" data-command=\"insertattach\"><i class=\"fa fa-paperclip\"></i></a>
							  	<input type=\"file\" id=\"file-new\" name=\"file-new[]\" multiple>
							  	<a href=\"#\" data-command=\"save-new\"><i class=\"fa fa-floppy-o\"></i></a>
							</div>
							<div id=\"inbox-new-body\" class=\"new-content\" contenteditable>
							</div>
							<input type=\"submit\" name=\"send_message\" value=\"Send\"/>

							<div class=\"image-list-new\">

							</div>
							<div class=\"attach-list-new\" id=\"attach-list-new\">

							</div>
						</form>
					</div>
			";
	} elseif(isset($messageid) && ($filter != "drafts" || $filter != "sent" || $filter == "" || $filter == null)){
		//verify if message has been read by the recipient
			$markChange = 1;
			//$sqlRead = "SELECT * FROM inbox WHERE idinbox = $messageid";
			$sqlRead = "SELECT * FROM inbox WHERE idinbox = $messageid";
			$rRead = mysqli_query($conn, $sqlRead) or die(mysqli_error($conn));
			
			if(mysqli_num_rows($rRead) > 0){
				$row = mysqli_fetch_assoc($rRead) or die(mysqli_error($conn));
					if ($row['readMark'] != $markChange && $_SESSION['iduser'] == $row['touser']){
					//$sqlMarkRead = "UPDATE inbox SET readMark = ? WHERE idinbox = ?";
					$sqlMarkRead = "UPDATE inbox SET readMark = ? WHERE idinbox = ?";
					$stmt = mysqli_prepare($conn, $sqlMarkRead) or die(mysqli_error($conn));
					mysqli_stmt_bind_param($stmt, "dd", $markChange, $messageid) or die(mysqli_error($conn));
					mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
					mysqli_stmt_close($stmt);
				}
			//Display Messages
				//$sqlMessage = "SELECT * FROM inbox WHERE idinbox = $messageid";
				$sqlMessage = "SELECT * FROM inbox WHERE idinbox = $messageid";
				$r = mysqli_query($conn, $sqlMessage) or die(mysqli_error($conn));
				
				while($row = mysqli_fetch_assoc($r)){
					$messageID = $row['idinbox'];
					$subject = $row['subject'];
					if($subject == ""){
						$subject = "(no subject)";
					}
					$bodyMessage = $row['body'];
					$flag = $row['flag'];
					$date = $row['senddate'];
						$date = date("d-m-Y", strtotime($date));
					$time = $row['sendtime'];
						$time = date("H:i", strtotime($time));
					$fromid = $row['fromuser'];
					$file = $row['file'];
					$from = "";
	
					if($fromid == "0"){
						$from = "SYSTEM NOTIFICATIONS";
					} else {
						$sqlFromName = "SELECT username FROM shutuser WHERE iduser = $fromid";
						$rFrom = mysqli_query($conn, $sqlFromName) or die(mysqli_error($conn));
						$from = mysqli_fetch_assoc($rFrom)['username'];
					}
					if($flag == 1){
						echo "<div class=\"message-flagged\"><p>THIS MESSAGE HAS BEEN FLAGGED. IT IS UNDER REVIEW BY A STAFF MEMBER</p></div>";
					}
					echo "<div class=\"inbox-view-message\">
							<header>
								<h2>From <span>$from</span></h2>
								<h3>$date, at $time</h3>
								<div>
									<a class=\"open-reply\" href=\"#inbox-reply\"><img src=\"resources/img/icons/next.png\"></a>
									<div></div>
									<a id=\"trash-submit-2\" href=\"#\" data-messid=\"$messageID\"><img src=\"resources/img/icons/trash-black.png\"></a>
								</div>";
								if($file){
								echo "<h4><a href=\"$file\">Download Attachment</a></h4>";
							}
							echo "</header>
							<h1>$subject</h1>
							<div class=\"message-body\" id=\"message-body\">
								$bodyMessage
							</div>";
						if($flag != 1){
							echo "<form id=\"inbox-reply\" method=\"post\" action=\"server/replyMessage.php\" enctype=\"multipart/form-data\">
								<p><a href=\"#\" class=\"close-reply\">x</a></p>
								<input type=\"hidden\" id=\"message_id\" name=\"message_id\" value=\"$messageID\">
								<input type=\"hidden\" id=\"from_reply\" name=\"from_reply\" value= \"$userid\"/>
								<input type=\"hidden\" id=\"subject_reply\" name=\"subject_reply\" value= \"Re: $subject\"/>
								<input type=\"text\" id=\"to_reply\" name=\"to_reply\" value=\"$from\"/>
								<div class=\"text-editor-bar\">
									<div class=\"text-wrapper\">Text Types
										<ul>
								  			<li><a href=\"#\" data-command=\"h1\"><h1>Header 1</h1></a></li>
								  			<li><a href=\"#\" data-command=\"h2\"><h2>Header 2</h2></a></li>
								  			<li><a href=\"#\" data-command=\"h3\"><h3>Header 3</h3></a></li>
								  			<li><a href=\"#\" data-command=\"h4\"><h4>Header 4</h4></a></li>
								  			<li><a href=\"#\" data-command=\"p\"><p>Normal Text</p></a></li>
								  		</ul>
									</div>
									
								  	<div class=\"font-wrapper\"><i class='fa fa-text-height'></i>
								  		<ul>
								  			<li><a href=\"#\" data-command=\"fontSize\" data-value=\"1\"><font size=\"1\">X-Small</font></a></li>
								  			<li><a href=\"#\" data-command=\"fontSize\" data-value=\"2\"><font size=\"1\">Small</font></a></li>
								  			<li><a href=\"#\" data-command=\"fontSize\" data-value=\"3\"><font size=\"3\">Normal</font></a></li>
								  			<li><a href=\"#\" data-command=\"fontSize\" data-value=\"4\"><font size=\"4\">Large</font></a></li>
								  			<li><a href=\"#\" data-command=\"fontSize\" data-value=\"5\"><font size=\"5\">X-Large</font></a></li>
								  			<li><a href=\"#\" data-command=\"fontSize\" data-value=\"6\"><font size=\"6\">XX-Large</font></a></li>
								  			<li><a href=\"#\" data-command=\"fontSize\" data-value=\"7\"><font size=\"7\">Largest</font></a></li>
								  		</ul>
								  	</div>
	
								  	<div class=\"editor-break\"></div>
	
								  	<div class=\"fore-wrapper\"><i class='fa fa-font'></i>
								    	<div class=\"fore-palette\">
								   		</div>
								  	</div>
								  	<a href=\"#\" data-command=\"bold\"><i class=\"fa fa-bold\"></i></a>
								  	<a href=\"#\" data-command=\"italic\"><i class=\"fa fa-italic\"></i></a>
								  	<a href=\"#\" data-command=\"underline\"><i class=\"fa fa-underline\"></i></a>
								  	<a href=\"#\" data-command=\"strikeThrough\"><i class=\"fa fa-strikethrough\"></i></a>
								  	<a href=\"#\" data-command=\"blockquote\"><i class=\"fa fa-quote-right\"></i></a>
	
									<div class=\"editor-break\"></div>
	
								  	<a href=\"#\" data-command=\"justifyLeft\"><i class=\"fa fa-align-left\"></i></a>
								  	<a href=\"#\" data-command=\"justifyCenter\"><i class=\"fa fa-align-center\"></i></a>
								  	<a href=\"#\" data-command=\"justifyRight\"><i class=\"fa fa-align-right\"></i></a>
								  	<a href=\"#\" data-command=\"justifyFull\"><i class=\"fa fa-align-justify\"></i></a>
	
									<div class=\"editor-break\"></div>
	
									<a href=\"#\" data-command=\"insertOrderedList\"><i class=\"fa fa-list-ol\"></i></a>
									<a href=\"#\" data-command=\"insertUnorderedList\"><i class=\"fa fa-list-ul\"></i></a>
								  	<a href=\"#\" data-command=\"indent\"><i class=\"fa fa-indent\"></i></a>
								  	<a href=\"#\" data-command=\"outdent\"><i class=\"fa fa-outdent\"></i></a>
	
								  	<div class=\"editor-break\"></div>
	
								  	<a href=\"#\" data-command=\"insertImage\"><i class=\"fa fa-image\"></i></a>
								  	<a href=\"#\" data-command=\"insertattach\"><i class=\"fa fa-paperclip\"></i></a>
								  	<input type=\"file\" id=\"file-reply\" name=\"file-reply[]\" multiple/>
								  	<a href=\"#\" data-command=\"save-reply\"><i class=\"fa fa-floppy-o\"></i></a>
								</div>
								<div id=\"inbox-reply-body\" class=\"reply-content\" contenteditable>
								</div>

								<input type=\"submit\" name=\"reply\" value=\"Send\">

								<div class=\"image-list-reply\">
	
								</div> 
								<div class=\"attach-list-reply\" id=\"attach-list-reply\">
	
								</div>
							</form>
							<form method=\"post\" action=\"server/daveDraft.php\" id=\"save-draft-form\">

							</form>";
						}
					echo "</div>";
				}
			} else {
				echo "<div class=\"inbox-empty\">
					<p>select a message</p>
				</div>";
			}
	} 
?>