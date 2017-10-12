$(document).ready(function() {
/*temporary functionality*/
	$(".open-reply").click(function(){
		$("#inbox-reply").css({display: "block"});
	});

	$(".close-reply").click(function(){
		$("#inbox-reply").css({display: "none"});
	});

//folder list
	$(".inbox-nav div a").click(function(){
		var folderlist = $(".extra-folders");
		originalHeight = folderlist.height();
		//folderlist.toggleClass("view-folders");
		if (folderlist.hasClass('view-folders')) {
        	$(".view-folders").css({overflowY:"hidden"});
        	setTimeout(function(){
        		folderlist.removeClass('view-folders');
        	}, 600);
        	folderlist.height(originalHeight-300);
    	} else {
    		
        	folderlist.addClass('view-folders');
        	folderlist.height(originalHeight+300);
        	setTimeout(function(){
        		$(".view-folders").css({overflowY:"auto"});
    		}, 400);
    	}
	});

//New message form submission
	var newDraft = 0;
	$('#inbox-new').on('submit', function(e){
		e.preventDefault();
		var form = e.target;

		if(newDraft == 0){
			$.ajax({
				type: "GET",
				url: "server/getMessageID.php",
				data: {},

				success: function(id){
					var messid = parseInt(id)+1;
					imgS = [];
					$("#inbox-new-body").find("img").each(function(){
						if($(this).attr("data-sub-src")){
							var imgO = $(this).attr("data-sub-src");
							var imgN = imgO.replace("postid", messid);
							$(this).attr("src", imgN);
							$(this).removeAttr("data-sub-src");
						}
					});
					$(".image-list-new").find("input[type='file']").each(function(){
						imgS.push($(this));
					});
					var content = $('#inbox-new-body').html();
					var imgArray = JSON.stringify(imgS);

					var fd = new FormData(form);
					fd.append('body', content);
					fd.append('image', imgArray);

					$.ajax({
						type: 'POST', 
						url: "server/sendMessage.php",
						data: fd,
						contentType: false,
    				    processData: false, 
						success: function(done){
							alert(done);
							window.location.href = "inbox.php";
						}
					});
				}
			});
		} else if(newDraft == 1){
			$.ajax({
				type: "GET",
				url: "server/getMessageID.php",
				data: {},

				success: function(id){
					var messid = parseInt(id)+1;
					imgS = [];

					$("#inbox-new-body").find("img").each(function(){
						if($(this).attr("data-sub-src")){
							var imgO = $(this).attr("data-sub-src");
							var imgN = imgO.replace("postid", messid);
							$(this).attr("src", imgN);
							$(this).removeAttr("data-sub-src");
						}
					});
					$(".image-list-new").find("input[type='file']").each(function(){
						imgS.push($(this));
					});
						
					var draft = 3;
					
					var content = $('#inbox-new-body').html();
					var imgArray = JSON.stringify(imgS);

					var fd2 = new FormData(form);	
					fd2.append('body', content);
					fd2.append('draftbool', draft);
					fd2.append('image', imgArray);

					$.ajax({
						type: 'POST',
						url: "server/saveDraft.php",
						data: fd2,
						contentType: false,
						processData: false,
						success: function(id){
							var done = "Message Draft has been saved";
							alert(done);

							var imid = parseInt(id);
					
							$("#inbox-new-body").find("img").each(function(){
								var imgB = $(this).attr("src");
								var imgF = imgB.replace(messid, imid);
								$(this).attr("src", imgF);
							});
							var finalbod = $('#inbox-new-body').html();
							var finald = 4;

							var fd3 = new FormData();
							fd3.append('id_mess', imid);
							fd3.append('body', finalbod);
							fd3.append('draftbool', finald);

							$.ajax({
								type: 'POST',
								url: "server/saveDraft.php",
								data: fd3,
								contentType: false,
								processData: false,
								success: function(done){
									alert(done);
									newDraft = 0;
								}
							});
						}
					});
				}
			});
		}
	});

//Reply Message form submission
	var replyDraft = 0;
	$('#inbox-reply').on('submit', function(e){
		e.preventDefault();
		var form = e.target;

		if(replyDraft == 0){
			$.ajax({
				type: "GET",
				url: "server/getMessageID.php",
				data: {},

				success: function(id){
					var messid = parseInt(id)+1;
					imgS = [];
					
					$("#inbox-reply-body").find("img").each(function(){
						if($(this).attr("data-sub-src")){
							var imgO = $(this).attr("data-sub-src");
							var imgN = imgO.replace("postid", messid);
							$(this).attr("src", imgN);
							$(this).removeAttr("data-sub-src");
						}
					});
					$(".image-list-reply").find("input[type='file']").each(function(){
						imgS.push($(this));
					});
					var prevMessage = $('#message-body').html();
			
					var content = $('#inbox-reply-body').html();
					var body = content + "<br />\n<hr><br />\n<div id=\"reply-message\">" + prevMessage + "</div>";
					var imgArray = JSON.stringify(imgS);
					
					var fd = new FormData(form);
					fd.append('body', body);
					fd.append('image', imgArray);
	
					$.ajax({
						type: 'POST', 
						url: "server/replyMessage.php",
						data: fd,
						contentType: false,
    				    processData: false, 
						success: function(done){
							alert(done);
							window.location.href = "inbox.php";
						}
					});
				}
			});
		} else if(replyDraft == 1){
			$.ajax({
				type: "GET",
				url: "server/getMessageID.php",
				data: {},

				success: function(id){
					var messid = parseInt(id)+1;
					imgS = [];

					$("#inbox-reply-body").find("img").each(function(){
						if($(this).attr("data-sub-src")){
							var imgO = $(this).attr("data-sub-src");
							var imgN = imgO.replace("postid", messid);
							$(this).attr("src", imgN);
							$(this).removeAttr("data-sub-src");
						}
					});
					$(".image-list-reply").find("input[type='file']").each(function(){
						imgS.push($(this));
					});
					var prevMessage = $('#message-body').html();
						
					var draft = 2;
					
					var content = $('#inbox-reply-body').html();
					var body = content + "<br />\n<hr><br />\n<div id=\"reply-message\">" + prevMessage + "</div>";
					var imgArray = JSON.stringify(imgS);

					var fd2 = new FormData(form);	
					fd2.append('body', content);
					fd2.append('draftbool', draft);
					fd2.append('image', imgArray);

					$.ajax({
						type: 'POST',
						url: "server/saveDraft.php",
						data: fd2,
						contentType: false,
						processData: false,
						success: function(id){
							var imid = parseInt(id);
					
							$("#inbox-reply-body").find("img").each(function(){
								var imgB = $(this).attr("src");
								var imgF = imgB.replace(messid, imid);
								$(this).attr("src", imgF);
							});
							var finalbod = $('#inbox-reply-body').html() + "<br />\n<hr><br />\n<div id=\"reply-message\">" + prevMessage + "</div>";
							var finald = 4;

							var fd3 = new FormData();
							fd3.append('id_mess', imid);
							fd3.append('body', finalbod);
							fd3.append('draftbool', finald);

							$.ajax({
								type: 'POST',
								url: "server/saveDraft.php",
								data: fd2,
								contentType: false,
								processData: false,
								success: function(done){
									alert(done);
									replyDraft = 0;
								}
							});
						}
					});
				}
			});
		}
	});

//Save Draft of Reply Message Form Submission
	$('a[data-command="save-reply"]').click(function(){
		replyDraft=1;
		$("#inbox-reply").submit();
	});
//Save Draft of New Message Form Submission
	$('a[data-command="save-new"]').click(function(){
		newDraft=1;
		$("#inbox-new").submit();
	});

//Save Draft
	$('a[data-command="save-draft"]').click(function(){
		var content = $('#inbox-draft-body').html();
		var draft = 1;
		var from = $("#from_draft").val();
		var to = $("#to_draft").val();
		var subject = $("#subject_draft").val();
		var message = $("#message_id").val();

		var fd = new FormData();	
		fd.append('body', content);
		fd.append('draftbool', draft);
		fd.append('from_new', from);
		fd.append('to_new', to);
		fd.append('subject_new', subject);
		fd.append('idmessage', message);

		$.ajax({
			type: 'POST', 
			url: "server/saveDraft.php",
			data: fd,
			contentType: false,
    	    processData: false, 
		});
		alert("Draft Saved");
		location.reload();
	});

	$('#inbox-draft-send').submit(function(e){
		e.preventDefault();
		var form = e.target;
		var content = $('#inbox-draft-body').html();
		var fd = new FormData(form);
		fd.append('body', content);

		$.ajax({
			type: 'POST', 
			url: "server/saveDraft.php",
			data: fd,
			contentType: false,
    	    processData: false, 
			success: function(done){
				alert(done);
				window.location.href = "inbox.php";
			}
		});
	});

//Select All messages in Inbox
	$("#select-all-messages").click(function(){
		var checkedStatus = this.checked;
    	$(".check").find('#select-message').each(function () {
        	$(this).prop('checked', checkedStatus);
        });
	});
//Select All sent messages
	$("#select-all-sent").click(function(){
		var checkedStatus = this.checked;
    	$(".check").find('#select-sent-message').each(function () {
        	$(this).prop('checked', checkedStatus);
        });
	});
//Select All draft messages
	$("#select-all-drafts").click(function(){
		var checkedStatus = this.checked;
    	$(".check").find('#select-draft-message').each(function () {
        	$(this).prop('checked', checkedStatus);
        });
	});
//Select All trash messages
	$("#select-all-trash").click(function(){
		var checkedStatus = this.checked;
    	$(".check").find('#select-trash-message').each(function () {
        	$(this).prop('checked', checkedStatus);
        });
	});

//Order By
	$("#sort-by-messages").on("change", function(){
		var orderby = $("#sort-by-messages").children("option").filter(":selected").text();
		if(orderby == "Subject"){
			orderby = "subject";
		} else if(orderby == "From"){
			orderby = "fromuser";
		} else if(orderby == "Date"){
			orderby = "idinbox";
		}

		window.location.replace("inbox.php?orderby="+orderby);
	});

//Mark as unread
	$("#unread-mark-activate").click(function (){
		$("#unread-submit").submit();
	});
	$("#unread-submit").on("submit", function (e){
		e.preventDefault();
		var form = e.target;
		$(".check").find('#select-message:checked').each(function (){
			var id = $(this).val();
			var fd = new FormData(form);
			fd.append('message-id', id);
			$.ajax({
				type: 'POST', 
				url: "server/markunread.php",
				data: fd,
				contentType: false,
    	    	processData: false, 
			});

		});
		window.location.replace("inbox.php");
	});

//Move to trash
	$("#trash-submit > a").click(function(){
		$("#trash-submit").submit();
	});

	$("#trash-submit").on("submit", function(e){
		e.preventDefault();
		var form = e.target;		

		if($("#trash-submit > a").text() == "Trash"){
			$(".check").find('#select-message:checked').each(function (){
				var id = $(this).val();
				var fd = new FormData(form);
				fd.append('message-id', id);
				$.ajax({
					type: 'POST', 
					url: "server/movetrash.php",
					data: fd,
					contentType: false,
    	    		processData: false
				});
			});
			window.location.replace("inbox.php");
		} else if($("#trash-submit > a").text() == "Delete Forever"){
			var check = confirm("Are you sure? This cannot be undone");
			if(check == true){
				if(window.location.href.indexOf("drafts") > -1) {
       				if(check == true){
       					$(".check").find('#select-draft-message:checked').each(function (){
							var id = $(this).val();
							var fd = new FormData(form);
							fd.append('message-id', id);
							$.ajax({
								type: 'POST', 
								url: "server/deleteDraft.php",
								data: fd,
								contentType: false,
   	   					 		processData: false
							});
						});
	    		   		window.location.replace("inbox.php");
       				}
    			} else {
					$(".check").find('#select-trash-message:checked').each(function (){
						var id = $(this).val();
						var fd = new FormData(form);
						fd.append('message-id', id);
						$.ajax({
							type: 'POST', 
							url: "server/deletemessage.php",
							data: fd,
							contentType: false,
   	   				 		processData: false
						});
       					window.location.replace("inbox.php");
					}); 
				}
			}
		}
	});
	
	$("#trash-submit-2").click(function(e){
		e.preventDefault();
		var id = $("#trash-submit-2").data("messid");
		var fd = new FormData();
		fd.append('message-id', id);
		$.ajax({
				type: 'POST', 
				url: "server/movetrash.php",
				data: fd,
				contentType: false,
    	    	processData: false
			});	
		window.location.replace("inbox.php");
	});

//New Folder
	$("#toggle-new-folder").click(function (){
		$("#popup").css("display", "block");
		$("#popup").css("z-index", "9999");

	});
	$("#close-popup").click(function (){
		$("#popup").css("display", "none");
		$("#popup").css("z-index", "");
	});
//Move To Folder
	$(".folder-move").click(function (){
		var fid = $(this).data("folderid");
		if(window.location.href.indexOf("trash") > -1){
			$(".check").find('#select-trash-message:checked').each(function (){
				var id = $(this).val();
				fd = new FormData();
				fd.append("folder-id", fid);
				fd.append("message-id", id);
				$.ajax({
					type: 'POST', 
					url: "server/moveFolder.php",
					data: fd,
					contentType: false,
    		    	processData: false, 
    		    	success: function(done){
    		    		alert(done);
    		    	}
				});
			});
			
		} else {
			$(".check").find('#select-message:checked').each(function (){
				var id = $(this).val();
				fd = new FormData();
				fd.append("folder-id", fid);
				fd.append("message-id", id);
				$.ajax({
					type: 'POST', 
					url: "server/moveFolder.php",
					data: fd,
					contentType: false,
    		    	processData: false, 
    		    	success: function(done){
    		    		alert(done);
    		    	}
				});
			});
		}
		window.location.replace("inbox.php");
	});

//Delete Folder
	$(".folder-delete").click(function (){
		var check = confirm("Are you sure you want to delete this folder? Doing so does not delete your messages, and they will be available in the Inbox.");
		if(check == true){
			var fid = $(this).data("folderid");
			fd = new FormData();
			fd.append("folder-id", fid);
			$.ajax({
				type: 'POST', 
				url: "server/deleteFolder.php",
				data: fd,
				contentType: false,
    		   	processData: false, 
    		   	success: function(done){
    		   		alert(done);
    		   	}
			});
			window.location.replace("inbox.php");
		}
	});

//Flag Message
	$("#flag-mail").click(function (){
		if(window.location.href.indexOf("drafts") === -1 || window.location.href.indexOf("sent") === -1){
			$(".check").find('#select-message:checked').each(function (){
				var id = $(this).val();
				fd = new FormData();
				fd.append("message-id", id);
				$.ajax({
					type: 'POST', 
					url: "server/flagMessage.php",
					data: fd,
					contentType: false,
    		    	processData: false, 
    		    	success: function(done){
    		    		alert(done);
    		    	}
				});
			});
			window.location.replace("inbox.php");
		}
	});

//Unflag Message
	$("#unflag-mail").click(function (){
		if(window.location.href.indexOf("drafts") === -1 || window.location.href.indexOf("sent") === -1){
			$(".check").find('#select-message:checked').each(function (){
				var id = $(this).val();
				fd = new FormData();
				fd.append("message-id", id);
				$.ajax({
					type: 'POST', 
					url: "server/unflagMessage.php",
					data: fd,
					contentType: false,
    		    	processData: false, 
    		    	success: function(done){
    		    		alert(done);
    		    	}
				});
			});
			window.location.replace("inbox.php");
		}
	});


});