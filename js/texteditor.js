$(document).ready(function() {
	/*Color pallete*/
		var colorPalette = ['000000', 'FF9966', '6699FF', '99FF66', 'CC0000', '00CC00', '0000CC', '333333', '0066FF', 'FFFFFF'];
		var forePalette = $('.fore-palette');
		for (var i = 0; i < colorPalette.length; i++) {
		    forePalette.append('<a href="#" data-command="forecolor" data-value="' + '#' +
		     colorPalette[i] + '" style="background-color:' + '#' + colorPalette[i] + 
		     ';" class="palette-item"></a>');
		}

		$('.text-editor-bar a').click(function(e) {	
			var command = $(this).data('command');

			if(command == 'h1' || command == 'h2' || command == 'h3' ||
				command == 'h4' || command == 'blockquote' || command == 'p'){
				document.execCommand('formatBlock', false, command);
			}
			if(command=='forecolor'){
				document.execCommand($(this).data('command'), false, $(this).data('value'));
			}
			if(command == 'fontSize'){
				document.execCommand($(this).data('command'), false, $(this).data('value'));
			}
			if (command == 'insertattach'){
   				$(this).next().trigger('click');
			}


			if (command == 'insertImage'){
				if($(".inbox-view-message").css("display") == "block"){
					var img = inputReply();	
					$('.image-list-reply').append(img);	
				} else if($(".inbox-new-message").css("display") == "block"){
					var img = inputNew();
					$('.image-list-new').append(img);
				}
				
				setTimeout(function(){
        			$(img).click();
    			}, 200);

				$(img).change(function() {
					fullPath = $(this).val();
					if (fullPath) {
					    var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
					    var filename = fullPath.substring(startIndex);
					    if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
					        filename = filename.substring(1);
					    }
					}
					if(window.FileReader){
						var file = img.files[0];
						var reader = new FileReader();
						if(file && file.type.match('image.*')){
							reader.readAsDataURL(file);
						}
						reader.onloadend = function(e){
							var s = reader.result;
							var src2 = "resources/img/inbox/postid/"+filename;
							var tag = '<img src="'+s+'" data-sub-src="'+src2+'" alt="'+filename+'">\n<br />\n';
							document.execCommand('insertHTML', false, tag);
						}
					}
   				});
			} else {
				document.execCommand($(this).data('command'), false, null);
			}
		});

			$("#file-new").change( function(){
				$("#attach-list-new").empty();
				newPreview(this);
			});
			$("#file-reply").change( function(){
				$("#attach-list-reply").empty();
				replyPreview(this);
			});
		
	/*Editor Commands List*/
		var imgCount = 1;

		function inputReply(){
			var input=document.createElement('input');
    				input.type="file";
    				input.class="image-reply-upload";
    				input.id="img-reply-"+imgCount;
    				input.name="image-reply[]";
    			var att = document.createAttribute("data-count");
    				att.value = imgCount;
    				input.setAttributeNode(att);
    			imgCount++;
    		return input;
		}
		function inputNew(){
			var input=document.createElement('input');
    				input.type="file";
    				input.class="image-new-upload";
    				input.id="img-new-"+imgCount;
    				input.name="image-new[]";
    			var att = document.createAttribute("data-count");
    				att.value = imgCount;
    				input.setAttributeNode(att);
    			imgCount++;
    		return input;
		}

			function replyPreview(evt){
				var getFile = evt.files;
			
				for(var i=0, f; f = getFile[i]; i++){
					var reader = new FileReader();
    	    
    	    		reader.onload = (function (theFile) {
    	    			return function(e) {
          					// Render thumbnail.
          					var div = document.createElement('div');
          					var size = formatBytes(theFile.size);
          			
          					div.innerHTML = ['<p>', escape(theFile.name), '<span>', size, '</span></p>'].join('');
          			
          					document.getElementById("attach-list-reply").appendChild(div, null);
        				};
		      		})(f);

    				reader.readAsDataURL(f);

				}
    	
			}
			function newPreview(evt){
				var getFile = evt.files;
			
				for(var i=0, f; f = getFile[i]; i++){
					var reader = new FileReader();
    	    
    	    		reader.onload = (function (theFile) {
    	    			return function(e) {
          					// Render thumbnail.
          					var div = document.createElement('div');
          					var size = formatBytes(theFile.size);
          			
          					div.innerHTML = ['<p>', escape(theFile.name), '<span>', size, '</span></p>'].join('');
          			
          					document.getElementById("attach-list-new").appendChild(div, null);
        				};
		      		})(f);

    				reader.readAsDataURL(f);

				}
    	
			}
			//get filesize
			function formatBytes(bytes,decimals) {
   				if(bytes == 0) return '0 Bytes';
   				var k = 1000,
       				dm = decimals + 1 || 3,
       				sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
       				i = Math.floor(Math.log(bytes) / Math.log(k));
   				return parseFloat((Math.round(bytes / Math.pow(k, i)).toFixed(dm))) + ' ' + sizes[i];
			}
});