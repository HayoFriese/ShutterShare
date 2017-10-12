<?php
  include 'server/db_conn.php';
  require_once('functions.php');
  if((!isset($_SESSION['logged-in']) && $_SESSION['logged-in'] != true)){
		echo pageIni("Access Denied");
    	echo error();
	} else {
  		echo pageIni("Inbox - Shuttershare");
  		echo nav2();
  		echo nav3("", "", "", " class=\"active\"");

  		$userid = $_SESSION['iduser'];
  		$messageid = isset($_GET['messageID']) ? $_GET['messageID']:null;
  		$order = isset($_GET['orderby']) ? $_GET['orderby']:null;

  		if(isset($_GET['filter'])){
  			$getfilter = isset($_GET['filter']) ? $_GET['filter']:null;
  			if (isset($_GET['orderby'])) {
  				$filter = "filter=$getfilter&orderby=$order&";
  			} else {
  				$filter = "filter=$getfilter&";
  			}
  		} else {
  			$filter = "";
  		}
  		echo newFolder($userid);
?> 
		<div class="back-end">
			<section class="inbox">
				<div>
					<ul>
						<li><a href="?messageID=new" id="new-mail"><img src="resources/img/icons/edit-black.png">New Mail</a></li>
						<?php
							if(isset($_GET['filter']) && ($_GET['filter'] == "sent" || $_GET['filter'] == "drafts")){
								echo "";
							} else {
								echo "<li><form enctype=\"multipart/form\" id=\"unread-submit\"><a href=\"#\" id=\"unread-mark-activate\"><img src=\"resources/img/icons/read-black.png\">Mark as Unread</a></form></li>";
							}
							if(isset($_GET['filter']) && ($_GET['filter'] == "trash" || $_GET['filter'] == "drafts")){
								echo "<li><form enctype=\"multipart/form\" id=\"trash-submit\"><a href=\"#\"><img src=\"resources/img/icons/trash-black.png\">Delete Forever</a></form></li>";
							} elseif(isset($_GET['filter']) && $_GET['filter'] == "sent"){
								echo "";
							} else{
								echo "<li><form enctype=\"multipart/form\" id=\"trash-submit\"><a href=\"#\"><img src=\"resources/img/icons/trash-black.png\">Trash</a></form></li>";
							}
							if(isset($_GET['filter']) && ($_GET['filter'] == "sent" || $_GET['filter'] == "drafts")){
								echo "";
							}else{
								echo "<li><a href=\"#\"><img src=\"resources/img/icons/folder-black.png\">Folder</a>
									<ul>
										<li><a href=\"#\" id=\"toggle-new-folder\">New Folder</a></li>
										<li><a href=\"#\">Move To Folder<span>></span></a>
										<ul>";
											$sqlMoveToFolder = "SELECT * FROM folders WHERE user = $userid ORDER BY idfolders ASC";
											$rMoveToFolder = mysqli_query($conn, $sqlMoveToFolder) or die(mysqli_error($conn));
											if(mysqli_num_rows($rMoveToFolder) > 0){
												if(isset($_GET['filter']) && $_GET['filter'] == "trash"){
													echo "<li><a class=\"folder-move\" data-folderid=\"1\" href=\"#\">Inbox</a></li>";
												} elseif(isset($_GET['filter'])) {
													echo "<li><a class=\"folder-move\" data-folderid=\"1\" href=\"#\">Remove From Folder</a></li>"; 
												}
												while($row = mysqli_fetch_assoc($rMoveToFolder)){
													$fid = $row['idfolders'];
													$fname = $row['folder'];

													echo "<li><a class=\"folder-move\" data-folderid=\"$fid\" href=\"#\">$fname</a></li>";
												}
											} else{
												echo "<li><a href=\"#\">(No Folders)</a></li>";
											}
										echo "</ul>
										</li>";
										if(mysqli_num_rows($rMoveToFolder) > 0){
											echo "<li><a href=\"#\" id=\"toggle-delete-folder\">Delete Folder <span>&gt;</span></a>
											<ul>";
											$sqlGetFolders = "SELECT * FROM folders WHERE user = $userid ORDER BY idfolders ASC";
											$rGetFolders = mysqli_query($conn, $sqlGetFolders) or die(mysqli_error($conn));
											while($row = mysqli_fetch_assoc($rGetFolders)){
												$fid = $row['idfolders'];
												$fname = $row['folder'];

												echo "<li><a class=\"folder-delete\" data-folderid=\"$fid\" href=\"#\">$fname</a></li>";
											}
											echo "</ul>
											</li>";
										}
									echo "</ul>
								</li>";
							}
							if(isset($_GET['filter'])){
								if(isset($_GET['filter']) && ($_GET['filter'] == "sent" || $_GET['filter'] == "drafts")){
									echo "";
								} else if(isset($_GET['filter']) && $_GET['filter'] == "flagged"){
									echo "<li><a href=\"#\" id=\"unflag-mail\"><img src=\"resources/img/icons/flag-black.png\">Unflag</a></li>";
								} else {
									echo "<li><a href=\"#\" id=\"flag-mail\"><img src=\"resources/img/icons/flag-black.png\">Flag</a></li>";	
								}
							} else {
								echo "<li><a href=\"#\" id=\"flag-mail\"><img src=\"resources/img/icons/flag-black.png\">Flag</a></li>";
							}
						?>
					</ul>
				</div>
				<article class="inbox-nav">
					<ul>
						<li <?php if(!isset($_GET['filter'])){ echo "class=\"active\"";}?>><a href="inbox.php">
							<img src="resources/img/icons/mail-black.png">
							Inbox
							<span>
								<?php 
									//$sqlInboxU = "SELECT * FROM inbox WHERE touser = $userid AND sent = 1 AND readMark = 0 AND folder != 2 AND folder != 0";
									$sqlInboxU = "SELECT * FROM inbox WHERE touser = $userid AND sent = 1 AND readMark = 0 AND folder != 2 AND folder != 0";
									$rInbox = mysqli_query($conn, $sqlInboxU) or die(mysqli_error($conn));
									$inboxCount = mysqli_num_rows($rInbox);
									if($inboxCount > 0){
										echo $inboxCount;
									}
								?>
							</span>
						</a></li>
						<li <?php if(isset($_GET['filter']) && $_GET['filter'] == "sent"){ echo "class=\"active\"";}?>><a href="?filter=sent">
							<img src="resources/img/icons/send-black.png">
							Sent
							<span>
							</span>
						</a></li>
						<li <?php if(isset($_GET['filter']) && $_GET['filter'] == "drafts"){ echo "class=\"active\"";}?>><a href="?filter=drafts">
							<img src="resources/img/icons/drafts-black.png">
							Drafts
							<span>
								<?php 
									$sqlDraftC = "SELECT * FROM inbox WHERE fromuser = $userid AND sent = 0";
									$rDraftC = mysqli_query($conn, $sqlDraftC) or die(mysqli_error($conn));
									$draftCount = mysqli_num_rows($rDraftC);
									if($draftCount > 0){
										echo $draftCount;
									}
								?>
							</span>
						</a></li>
						<li <?php if(isset($_GET['filter']) && $_GET['filter'] == "trash"){ echo "class=\"active\"";}?>><a href="?filter=trash">
							<img src="resources/img/icons/trash-black.png">
							Trash
							<span>
								<?php 
									$sqlTrashC = "SELECT * FROM inbox WHERE touser = $userid AND folder = 2";
									$rTrashC = mysqli_query($conn, $sqlTrashC) or die(mysqli_error($conn));
									$trashCount = mysqli_num_rows($rTrashC);
									if($trashCount > 0){
										echo $trashCount; 
									}
								?>
							</span>
						</a></li>
						<li <?php if(isset($_GET['filter']) && $_GET['filter'] == "flagged"){ echo "class=\"active\"";}?>><a href="?filter=flagged">
							<img src="resources/img/icons/flag-black.png">
							Flagged
							<span>
								<?php 
									$sqlFlagC = "SELECT * FROM inbox WHERE touser = $userid AND flag = 1 AND folder != 0";
									$rFlagC = mysqli_query($conn, $sqlFlagC) or die(mysqli_error($conn));
									$trashCount = mysqli_num_rows($rFlagC);
									if($trashCount > 0){
										echo $trashCount; 
									}
								?>
							</span>
						</a></li>
					</ul>
						<div><a id="toggle-folderlist" href="#">&bull; &bull; &bull;</a></div>
					<ul class="extra-folders">
						<?php
							$sqlListFolder = "SELECT * FROM folders WHERE user = $userid";
							$rListFolder = mysqli_query($conn, $sqlListFolder) or die(mysqli_error($conn));
							while($row = mysqli_fetch_assoc($rListFolder)){
								$fid = $row['idfolders'];
								$fname = $row['folder'];

								$sqlMessageInFolder = "SELECT * FROM inbox WHERE folder = $fid";
								$rMessageInFolder = mysqli_query($conn, $sqlMessageInFolder) or die(mysqli_error($conn));
								$fnum = mysqli_num_rows($rMessageInFolder);
								if(isset($_GET['filter']) && $_GET['filter'] == "$fid"){ 
									echo "<li class=\"active\"><a href=\"inbox.php?filter=$fid\">$fname<span>$fnum</span></a></li>";
								}else{
									echo "<li><a href=\"inbox.php?filter=$fid\">$fname<span>$fnum</span></a></li>";
								}
							}
						?>
					</ul>	
				</article>
				<article class="inbox-list">
					<header>
						<p>
							<?php
								if(isset($_GET['filter']) && $_GET['filter'] == "sent"){
									echo "<input type=\"checkbox\" id=\"select-all-sent\" name=\"select-all-sent\">";
								} elseif(isset($_GET['filter']) && $_GET['filter'] == "drafts"){
									echo "<input type=\"checkbox\" id=\"select-all-drafts\" name=\"select-all-drafts\">";
								} elseif(isset($_GET['filter']) && $_GET['filter'] == "trash"){
									echo "<input type=\"checkbox\" id=\"select-all-trash\" name=\"select-all-trash\">";
								} else {
									echo "<input type=\"checkbox\" id=\"select-all-messages\" name=\"select-all-messages\">";
								}
							?>
							<span>Sort By
								<select id="sort-by-messages">
									<option value="idinbox" <?php if($order=="idinbox"){echo "selected";} ?>>Date</option>
									<option value="fromuser" <?php if($order=="fromuser"){echo "selected";} ?>>From</option>
									<option value="subject" <?php if($order=="subject"){echo "selected";} ?>>Subject</option>
								</select>
							</span>
						</p>
					</header>
					<div>
						<?php
							if(isset($_GET['filter']) && $_GET['filter'] == "sent"){
								if($order){
									$sqlInbox = "SELECT * FROM inbox WHERE fromuser = $userid AND sent = 1 ORDER BY $order DESC, sendDate DESC, sendtime DESC";
								}
								else{
									$sqlInbox = "SELECT * FROM inbox WHERE fromuser = $userid AND sent = 1 ORDER BY sendDate DESC, sendtime DESC";
								}
								$r = mysqli_query($conn, $sqlInbox) or die(mysqli_error($conn));
								
								while($row = mysqli_fetch_assoc($r)){
									$idmessage = $row['idinbox'];
									$subject = $row['subject'];
									if($subject == ""){
										$subject = "(no subject)";
									}
									if($subject == ""){
										$subject = "(no subject)";
									}
									$body = strip_tags(str_replace( '<', ' <',$row['body']));
										$body = str_replace( '  ', ' ', $body );
									//validate body
									$senddate = $row['senddate'];
									$sendtime = $row['sendtime'];
									$read = $row['readMark'];
									$flag = $row['flag'];
									$sent = $row['sent'];
									$toid = $row['touser'];
									$fromid = $row['fromuser'];
									$folder = $row['folder'];
	
									$sqlToName = "SELECT username FROM shutuser WHERE iduser = $toid";
									$rTo = mysqli_query($conn, $sqlToName) or die(mysqli_error($conn));
	
									$to = mysqli_fetch_assoc($rTo)['username'];
	
									if($idmessage == $messageid){
										echo "<div class=\"active\">
												<div class=\"check\">
													<input type=\"checkbox\" id=\"select-sent-message\" name=\"select-sent-message\">
												</div>
												<div class=\"message-blob\">
													<a href=\"?".$filter."messageID=$idmessage\">
														<h1>$to <span>$senddate, at $sendtime</span></h1>
														<h2>$subject</h2>
														<p>$body</p>
														<a class=\"open-reply\" href=\"#inbox-reply\">
															<img src=\"resources/img/icons/reply-thin-black.png\">
														</a>
													</a>
												</div>
											</div>";
									} elseif($read == 1 || $read == 0){
										echo "
											<div class=\"read\">
												<div class=\"check\">
													<input type=\"checkbox\" id=\"select-sent-message\" name=\"select-sent-message\">
												</div>
												<div class=\"message-blob\">
													<a href=\"?".$filter."messageID=$idmessage\">
														<h1>$to <span>$senddate, at $sendtime</span></h1>
														<h2>$subject</h2>
														<p>$body</p>
														<a class=\"open-reply\" href=\"#inbox-reply\">
															<img src=\"resources/img/icons/reply-thin-black.png\">
														</a>
													</a>
												</div>
											</div>
										";
									}
								}
							} elseif(isset($_GET['filter']) && $_GET['filter'] == "drafts"){
								$sqlInbox = "SELECT * FROM inbox WHERE fromuser = $userid AND sent = 0 AND folder != 0 ORDER BY sendDate DESC, sendtime DESC";
								$r = mysqli_query($conn, $sqlInbox) or die(mysqli_error($conn));
								
								while($row = mysqli_fetch_assoc($r)){
									$idmessage = $row['idinbox'];
									$subject = $row['subject'];
									if($subject == ""){
										$subject = "(no subject)";
									}
									$body = strip_tags(str_replace( '<', ' <',$row['body']));
										$body = str_replace( '  ', ' ', $body );
									//validate body
									$senddate = $row['senddate'];
									$sendtime = $row['sendtime'];
									$read = $row['readMark'];
									$flag = $row['flag'];
									$sent = $row['sent'];
									$toid = $row['touser'];
									$folder = $row['folder'];
									$to = "";
									
									if($toid != "0"){
										$sqlToName = "SELECT username FROM shutuser WHERE iduser = $toid";
										$rTo = mysqli_query($conn, $sqlToName) or die(mysqli_error($conn));
										$to = mysqli_fetch_assoc($rTo)['username'];
									} else{
										$to = "(no recipient)";
									}
									
									if($idmessage==$messageid){
										echo "<div class=\"active\">
												<div class=\"check\">
													<input type=\"checkbox\" id=\"select-draft-message\" name=\"select-draft-message\" value=\"$idmessage\">
												</div>
												<div class=\"message-blob\">
													<a href=\"?".$filter."messageID=$idmessage\">
														<h1>$to <span>$senddate, at $sendtime</span></h1>
														<h2>$subject</h2>
														<p>$body</p>
														<a class=\"open-reply\" href=\"#inbox-reply\">
															<img src=\"resources/img/icons/reply-thin-black.png\">
														</a>
													</a>
												</div>
											</div>";
									} else{
										echo "
											<div class=\"read\">
												<div class=\"check\">
													<input type=\"checkbox\" id=\"select-draft-message\" name=\"select-draft-message\" value=\"$idmessage\">
												</div>
												<div class=\"message-blob\">
													<a href=\"?".$filter."messageID=$idmessage\">
														<h1>$to <span>$senddate, at $sendtime</span></h1>
														<h2>$subject</h2>
														<p>$body</p>
														<a class=\"open-reply\" href=\"#inbox-reply\">
															<img src=\"resources/img/icons/reply-thin-black.png\">
														</a>
													</a>
												</div>
											</div>
										";
									}	
								} 
							} elseif(isset($_GET['filter']) && $_GET['filter'] == "trash"){
								$sqlInbox = "SELECT * FROM inbox WHERE touser = $userid AND sent = 1 AND folder = 2 ORDER BY sendDate DESC, sendtime DESC";
								$r = mysqli_query($conn, $sqlInbox) or die(mysqli_error($conn));

								while($row = mysqli_fetch_assoc($r)){
									$idmessage = $row['idinbox'];
									$subject = $row['subject'];
									if($subject == ""){
										$subject = "(no subject)";
									}
									$body = strip_tags(str_replace( '<', ' <',$row['body']));
										$body = str_replace( '  ', ' ', $body );
									//validate body
									$senddate = $row['senddate'];
									$sendtime = $row['sendtime'];
									$read = $row['readMark'];
									$flag = $row['flag'];
									$sent = $row['sent'];
									$fromid = $row['fromuser'];
									$folder = $row['folder'];
									$from = "";

									if($fromid == 0){
										$from = "-- SYSTEM NOTIFICATIONS --";
									} else {
										$sqlFromName = "SELECT username FROM shutuser WHERE iduser = $fromid";
										$rFrom = mysqli_query($conn, $sqlFromName) or die(mysqli_error($conn));
										$from = mysqli_fetch_assoc($rFrom)['username'];
									}
									
									if($idmessage==$messageid){
										echo "<div class=\"active\">
												<div class=\"check\">
													<input type=\"checkbox\" id=\"select-trash-message\" name=\"select-trash-message\" value=\"$idmessage\">
												</div>
												<div class=\"message-blob\">
													<a href=\"?".$filter."messageID=$idmessage\">
														<h1>$from <span>$senddate, at $sendtime</span></h1>
														<h2>$subject</h2>
														<p>$body</p>
														<a class=\"open-reply\" href=\"#inbox-reply\">
															<img src=\"resources/img/icons/reply-thin-black.png\">
														</a>
													</a>
												</div>
											</div>";
									} elseif($read == 0){
										echo "
											<div class=\"unread\">
												<div class=\"check\">
													<input type=\"checkbox\" id=\"select-trash-message\" name=\"select-trash-message\"  value=\"$idmessage\">
												</div>
												<div class=\"message-blob\">
													<a href=\"?".$filter."messageID=$idmessage\">
														<h1>$from <span>$senddate, at $sendtime</span></h1>
														<h2>$subject</h2>
														<p>$body</p>
														<a class=\"open-reply\" href=\"#inbox-reply\">
															<img src=\"resources/img/icons/reply-thin-black.png\">
														</a>
													</a>
												</div>
											</div>
										";
									} elseif($read==1){
										echo "
											<div class=\"read\">
												<div class=\"check\">
													<input type=\"checkbox\" id=\"select-trash-message\" name=\"select-trash-message\" value=\"$idmessage\">
												</div>
												<div class=\"message-blob\">
													<a href=\"?".$filter."messageID=$idmessage\">
														<h1>$from <span>$senddate, at $sendtime</span></h1>
														<h2>$subject</h2>
														<p>$body</p>
														<a class=\"open-reply\" href=\"#inbox-reply\">
															<img src=\"resources/img/icons/reply-thin-black.png\">
														</a>
													</a>
												</div>
											</div>
										";
									}	
								} 
							}  elseif(isset($_GET['filter']) && $_GET['filter'] == "flagged"){
								if($order){
									$sqlInbox = "SELECT * FROM inbox WHERE touser = $userid AND sent = 1 AND folder != 2 AND folder != 0 AND flag = 1 ORDER BY $order DESC, sendDate DESC, sendtime DESC";
								} else {
									$sqlInbox = "SELECT * FROM inbox WHERE touser = $userid AND sent = 1 AND folder != 2 AND folder != 0 AND flag = 1 ORDER BY sendDate DESC, sendtime DESC";
								}
								$r = mysqli_query($conn, $sqlInbox) or die(mysqli_error($conn));
	
								while($row = mysqli_fetch_assoc($r)){
									$idmessage = $row['idinbox'];
									$subject = $row['subject'];
									if($subject == ""){
										$subject = "(no subject)";
									}
									$body = strip_tags(str_replace( '<', ' <',$row['body']));
										$body = str_replace( '  ', ' ', $body );
									//validate body
									$senddate = $row['senddate'];
										$senddate = date("d-m-Y", strtotime($senddate));
									$sendtime = $row['sendtime'];
										$sendtime = date("H:i", strtotime($sendtime));
									$read = $row['readMark'];
									$flag = $row['flag'];
									$sent = $row['sent'];
									$fromid = $row['fromuser'];
									$folder = $row['folder'];
	
									$from = "";

									if($fromid == 0){
										$from = "-- SYSTEM NOTIFICATIONS --";
									} else {
										$sqlFromName = "SELECT username FROM shutuser WHERE iduser = $fromid";
										$rFrom = mysqli_query($conn, $sqlFromName) or die(mysqli_error($conn));
										$from = mysqli_fetch_assoc($rFrom)['username'];
									}
	
									if($idmessage==$messageid){
										echo "<div class=\"active-flagged\">
												<div class=\"check\">
													<input type=\"checkbox\" id=\"select-message\" name=\"select-message\" value=\"$idmessage\">
												</div>
												<div class=\"message-blob\">
													<a href=\"?".$filter."messageID=$idmessage\">
														<h1>$from <span>$senddate, at $sendtime</span></h1>
														<h2>$subject</h2>
														<p>$body</p>
														<a class=\"open-reply\" href=\"#inbox-reply\">
															<img src=\"resources/img/icons/reply-thin-black.png\">
														</a>
													</a>
												</div>
											</div>";
									} else {
										echo "
											<div class=\"flagged\">
												<div class=\"check\">
													<input type=\"checkbox\" id=\"select-message\" name=\"select-message\"  value=\"$idmessage\">
												</div>
												<div class=\"message-blob\">
													<a href=\"?".$filter."messageID=$idmessage\">
														<h1>$from <span>$senddate, at $sendtime</span></h1>
														<h2>$subject</h2>
														<p>$body</p>
														<a class=\"open-reply\" href=\"#inbox-reply\">
															<img src=\"resources/img/icons/reply-thin-black.png\">
														</a>
													</a>
												</div>
											</div>";
									}
								}
							} elseif(isset($_GET['filter'])) {
								$folderid = $_GET['filter'];
								if($order){
									$sqlInbox = "SELECT * FROM inbox WHERE touser = $userid AND folder = $folderid AND sent = 1 ORDER BY $order DESC, sendDate DESC, sendtime DESC";
								} else {
									$sqlInbox = "SELECT * FROM inbox WHERE touser = $userid AND sent = 1 AND folder = $folderid ORDER BY sendDate DESC, sendtime DESC";
								}
								$r = mysqli_query($conn, $sqlInbox) or die(mysqli_error($conn));
	
								while($row = mysqli_fetch_assoc($r)){
									$idmessage = $row['idinbox'];
									$subject = $row['subject'];
									if($subject == ""){
										$subject = "(no subject)";
									}
									$body = strip_tags(str_replace( '<', ' <',$row['body']));
										$body = str_replace( '  ', ' ', $body );
									//validate body
									$senddate = $row['senddate'];
										$senddate = date("d-m-Y", strtotime($senddate));
									$sendtime = $row['sendtime'];
										$sendtime = date("H:i", strtotime($sendtime));
									$read = $row['readMark'];
									$flag = $row['flag'];
									$sent = $row['sent'];
									$fromid = $row['fromuser'];
									$folder = $row['folder'];
	
									$from = "";

									if($fromid == 0){
										$from = "-- SYSTEM NOTIFICATIONS --";
									} else {
										$sqlFromName = "SELECT username FROM shutuser WHERE iduser = $fromid";
										$rFrom = mysqli_query($conn, $sqlFromName) or die(mysqli_error($conn));
										$from = mysqli_fetch_assoc($rFrom)['username'];
									}
	
									if($idmessage==$messageid){
										echo "<div class=\"active\">
												<div class=\"check\">
													<input type=\"checkbox\" id=\"select-message\" name=\"select-message\" value=\"$idmessage\">
												</div>
												<div class=\"message-blob\">
													<a href=\"?".$filter."messageID=$idmessage\">
														<h1>$from <span>$senddate, at $sendtime</span></h1>
														<h2>$subject</h2>
														<p>$body</p>
														<a class=\"open-reply\" href=\"#inbox-reply\">
															<img src=\"resources/img/icons/reply-thin-black.png\">
														</a>
													</a>
												</div>
											</div>";
									} elseif($read == 0){
										echo "
											<div class=\"unread\">
												<div class=\"check\">
													<input type=\"checkbox\" id=\"select-message\" name=\"select-message\"  value=\"$idmessage\">
												</div>
												<div class=\"message-blob\">
													<a href=\"?".$filter."messageID=$idmessage\">
														<h1>$from <span>$senddate, at $sendtime</span></h1>
														<h2>$subject</h2>
														<p>$body</p>
														<a class=\"open-reply\" href=\"#inbox-reply\">
															<img src=\"resources/img/icons/reply-thin-black.png\">
														</a>
													</a>
												</div>
											</div>
										";
									} elseif($read==1){
										echo "
											<div class=\"read\">
												<div class=\"check\">
													<input type=\"checkbox\" id=\"select-message\" name=\"select-message\" value=\"$idmessage\">
												</div>
												<div class=\"message-blob\">
													<a href=\"?".$filter."messageID=$idmessage\">
														<h1>$from <span>$senddate, at $sendtime</span></h1>
														<h2>$subject</h2>
														<p>$body</p>
														<a class=\"open-reply\" href=\"#inbox-reply\">
															<img src=\"resources/img/icons/reply-thin-black.png\">
														</a>
													</a>
												</div>
											</div>
										";
									}	
								}
							} else {
								if($order){
									$sqlInbox = "SELECT * FROM inbox WHERE touser = $userid AND sent = 1 AND folder != 2 AND folder != 0 AND flag != 1 ORDER BY $order DESC, sendDate DESC, sendtime DESC";
								} else {
									$sqlInbox = "SELECT * FROM inbox WHERE touser = $userid AND sent = 1 AND folder != 2 AND folder != 0 AND flag != 1 ORDER BY sendDate DESC, sendtime DESC";
								}
								$r = mysqli_query($conn, $sqlInbox) or die(mysqli_error($conn));
	
								while($row = mysqli_fetch_assoc($r)){
									$idmessage = $row['idinbox'];
									$subject = $row['subject'];
									if($subject == ""){
										$subject = "(no subject)";
									}
									$body = strip_tags(str_replace( '<', ' <',$row['body']));
										$body = str_replace( '  ', ' ', $body );
									//validate body
									$senddate = $row['senddate'];
										$senddate = date("d-m-Y", strtotime($senddate));
									$sendtime = $row['sendtime'];
										$sendtime = date("H:i", strtotime($sendtime));
									$read = $row['readMark'];
									$flag = $row['flag'];
									$sent = $row['sent'];
									$fromid = $row['fromuser'];
									$folder = $row['folder'];
	
									$from = "";

									if($fromid == 0){
										$from = "-- SYSTEM NOTIFICATIONS --";
									} else {
										$sqlFromName = "SELECT username FROM shutuser WHERE iduser = $fromid";
										$rFrom = mysqli_query($conn, $sqlFromName) or die(mysqli_error($conn));
										$from = mysqli_fetch_assoc($rFrom)['username'];
									}
	
									if($idmessage==$messageid){
										echo "<div class=\"active\">
												<div class=\"check\">
													<input type=\"checkbox\" id=\"select-message\" name=\"select-message\" value=\"$idmessage\">
												</div>
												<div class=\"message-blob\">
													<a href=\"?".$filter."messageID=$idmessage\">
														<h1>$from <span>$senddate, at $sendtime</span></h1>
														<h2>$subject</h2>
														<p>$body</p>
														<a class=\"open-reply\" href=\"#inbox-reply\">
															<img src=\"resources/img/icons/reply-thin-black.png\">
														</a>
													</a>
												</div>
											</div>";
									} elseif($read == 0){
										echo "
											<div class=\"unread\">
												<div class=\"check\">
													<input type=\"checkbox\" id=\"select-message\" name=\"select-message\"  value=\"$idmessage\">
												</div>
												<div class=\"message-blob\">
													<a href=\"?".$filter."messageID=$idmessage\">
														<h1>$from <span>$senddate, at $sendtime</span></h1>
														<h2>$subject</h2>
														<p>$body</p>
														<a class=\"open-reply\" href=\"#inbox-reply\">
															<img src=\"resources/img/icons/reply-thin-black.png\">
														</a>
													</a>
												</div>
											</div>
										";
									} elseif($read==1){
										echo "
											<div class=\"read\">
												<div class=\"check\">
													<input type=\"checkbox\" id=\"select-message\" name=\"select-message\" value=\"$idmessage\">
												</div>
												<div class=\"message-blob\">
													<a href=\"?".$filter."messageID=$idmessage\">
														<h1>$from <span>$senddate, at $sendtime</span></h1>
														<h2>$subject</h2>
														<p>$body</p>
														<a class=\"open-reply\" href=\"#inbox-reply\">
															<img src=\"resources/img/icons/reply-thin-black.png\">
														</a>
													</a>
												</div>
											</div>
										";
									}	
								}
							}
						?>
					</div>
				</article>
				<article class="inbox-right-pane">
					<?php
						include "server/message.php";
					?>
				</article>
			</section>
		</div>
		<script src="server/libs/jquery-2.2.1.js"></script>
		<script src="server/js/texteditor.js"></script>
		<script src="server/js/inbox.js"></script>
<?php
	}
	echo pageClose();
?>