$("#image-upload").change( function(){
    $("#imagePreview").empty();
    readURL(this);
});

function readURL(evt){
    var getFile = evt.files;

    for(var i=0, f; f = getFile[i]; i++){
     if(!f.type.match('image.*')) {
         continue;
     }
     var reader = new FileReader();

        reader.onload = (function (theFile){
            return function(e) {
                var div = document.createElement('div');

                div.innerHTML = ['<div class="prevBlock">', '<img class=\"imageThumb\"  src="',e.target.result,
                    '"alt="', escape(theFile.name), '"/> <p>', escape(theFile.name), '</p>', '</div>'].join('');

                document.getElementById('imagePreview').insertBefore(div, null);
            };
        })(f);

        reader.readAsDataURL(f);
    }
}

$(document).ready(function (){
    var covimg = $(".active").attr("src");
    $("#cover-img").css('background-image', 'url("'+covimg+'")');

//CALENDAR THINGS 
    //Cost Calculation
        var costTotal = $("#hideCost").val();
        $("#dropOffDate").on("change", function(){
            var startD = $("#pickUpDate").val().split(/\//);
            var endD = $("#dropOffDate").val().split(/\//);
        
            var newdate1 = startD[1] + '/' + startD[0] + '/' + startD[2];
            var newdate2 = endD[1] + '/' + endD[0] + '/' + endD[2];
        
            var newd1 = newdate1.toString('mm/dd/yy');
            var newd2 = newdate2.toString('mm/dd/yy');
        
            var date1 = new Date(newd1);
            var date2 = new Date(newd2);
            var timeDiff = Math.abs(date2.getTime() - date1.getTime());
            var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
        
            var tot = parseInt(costTotal) * parseInt(diffDays);
            var costShow = $("#showCost").val();
            var costHide = $("#hideCost").val();
        
            var showChange = costShow.replace(costHide, tot);
        
            document.getElementById("hideCost").value = tot;
            document.getElementById("showCost").value = showChange;
        
        });
    
        $(".dropOffDate2").on("change", function(){
            var startD = $("#pickUpDate").val().split(/\//);
            var endD = $(".dropOffDate2").val().split(/\//);
        
            var newdate1 = startD[1] + '/' + startD[0] + '/' + startD[2];
            var newdate2 = endD[1] + '/' + endD[0] + '/' + endD[2];
        
            var newd1 = newdate1.toString('mm/dd/yy');
            var newd2 = newdate2.toString('mm/dd/yy');
        
            var date1 = new Date(newd1);
            var date2 = new Date(newd2);
            var timeDiff = Math.abs(date2.getTime() - date1.getTime());
            var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
        
            var tot = parseInt(costTotal) * parseInt(diffDays);
            var costShow = $("#showCost").val();
            var costHide = $("#costNew").val();
        
            var showChange = costShow.replace(costHide, tot);
        
            document.getElementById("costNew").value = tot;
            document.getElementById("showCost").value = showChange;
        });
    
    //Calendar stuff
        var range = [];
    
        var unavailable = function(date){
            var start = [],
            end = [];
            for(var c = 0; c <=dr.length; c++){
                if(c == 0 || c % 2 === 0){
                    start.push(dr[c]);
                } else {
                    end.push(dr[c]);
                }
            }
    
            for(var i = 0; i <= end.length; i++){
                var sd = start[i], ed = end[i];
                for (var d = new Date(sd); d <= new Date(ed); d.setDate(d.getDate() + 1)){
                    range.push($.datepicker.formatDate('yy-mm-dd', d));
                }
            }
    
    
            var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
            return [ range.indexOf(string) == -1];
        }
    
        $("#dropOffDate").datepicker({
            minDate: '+2D',
            dateFormat: "dd/mm/yy",
            beforeShowDay: unavailable
        });
    
        $("#pickUpDate").datepicker({
            minDate: '+2D',
            dateFormat: "dd/mm/yy",
            beforeShowDay: unavailable,
            onClose: function(selectedDate) {
                $("#dropOffDate").datepicker("option", "minDate", "+2D");
                /* edit to configure to different months & years */
                if(selectedDate != ""){
                    var d = selectedDate;
                    var parts = d.split('/');
                    var newDay = parseInt(parts[0]) +1;
                    var date = new Date();
                    date.setDate(newDay);
                    var result = date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear();
                    $("#dropOffDate").datepicker("option", "minDate", result);
                    return $("#dropOffDate").datepicker("show");
                }
            }
        });
    
    
        $('#link').click(function() {
            jQuery(this).text('+ View Calendar');
            if($('#calendar').is(':visible')){
                jQuery(this).text('+ View Calendar');
            }else{
                jQuery(this).text('- Hide Calendar');
            }
            $('#calendar').slideToggle('fast');
            return false;
        });
    
        $('.week-picker').datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            minDate: 0,
            beforeShowDay: unavailable,
            numberOfMonths: [6,2]
        });
    
        $('.week-picker .ui-datepicker-calendar tr').on('mousemove', function () {
            $(this).find('td a').addClass('ui-state-hover');
        });
        $('.week-picker .ui-datepicker-calendar tr').on('mouseleave', function () {
            $(this).find('td a').removeClass('ui-state-hover');
        });   

//Pop Up Stuffs
    $("#message-user-popup").click(function (){
        $(".messAdvert").css("display", "block");
        $(".messAdvert").css("z-index", "9999");

    });
    $("#message-advert-popup").click(function (){
        $(".messAdvert").css("display", "block");
        $(".messAdvert").css("z-index", "9999");

    });

    // admin

    $("#toggle-report-advert").click(function (){
        $(".defaultOption").removeAttr("selected");
        $(".issueReport").css("display", "block");
        $(".issueReport").css("z-index", "9999");
    });
    $(".toggle-report-review").click(function (){
        var complex = $(this).attr('data-revid');
        $("#subrevid").val(complex);
        $(".defaultOption").removeAttr("selected");
        $(".issueReportReview").css("display", "block");
        $(".issueReportReview").css("z-index", "9999");

    });
    $("#close-reviewad-up").click(function (){
        $(".issueReport").css("display", "none", "z-index", "");
        $(".defaultOption").attr("selected","selected");
        $(".issueReport textarea").val("");
    });
    $("#close-reviewrep-up").click(function (){
        $(".issueReportReview").css("display", "none", "z-index", "");
        $(".defaultOption").attr("selected","selected");
        $(".issueReportReview textarea").val("");
        $("#subrevid").val("");
    });

    // end admin

    var newDraft = 0;
    var advertid = $("#advert_message").val();

    $("#close-popup").click(function (){
        var sub = $("#subject-popup").val();
        var bod = $('textarea[name="body"]').val();

        if(sub || (sub && bod)){
            if(confirm("Do you want to save this message as a draft?") == true){
                newDraft = 1;
                $("#new-message").submit();

                $(".messAdvert").css("display", "none");
                $(".messAdvert").css("z-index", "");
            } else {
                $("#subject-popup").val("");
                $('textarea[name="body"]').val("");

                $(".messAdvert").css("display", "none");
                $(".messAdvert").css("z-index", "");
            }
        } else {
            $('textarea[name="body"]').val("");
            $(".messAdvert").css("display", "none");
            $(".messAdvert").css("z-index", "");
        }
    });
    $("#close-report").click(function (){
        $(".issueReport").css("display", "none");
        $(".issueReport").css("z-index", "");
    });


    $("#savedraft").click(function(){
        newDraft=1;
        $("#new-message").submit();
    });

    $('#new-message').on('submit', function(e){
        e.preventDefault();
        var form = e.target;

        if(newDraft == 0){
            $.ajax({
                type: "GET",
                url: "server/getMessageID.php",
                data: {},

                success: function(id){
                    var messid = parseInt(id)+1;

                    var fd = new FormData(form);

                    $.ajax({
                        type: 'POST', 
                        url: "server/sendMessage.php",
                        data: fd,
                        contentType: false,
                        processData: false, 
                        success: function(id){
                            var done = "Message has been sent";
                            alert(done);
                            window.location.href = "viewAdvert.php?id="+advertid;
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

                    var draft = 3;

                    var fd2 = new FormData(form);   
                    fd2.append('draftbool', draft);

                    $.ajax({
                        type: 'POST',
                        url: "server/saveDraft.php",
                        data: fd2,
                        contentType: false,
                        processData: false,
                        success: function(id){
                            var done = "Message Draft has been saved. You can edit this in the Inbox ";
                            alert(done);
                            window.location.href = "viewAdvert.php?id="+advertid;
                        }
                    });
                }
            });
        }
    });

//Cover Image
    $(".pics > ul > li > img").click( function(){
        $(".pics > ul > li > img").each( function(){
            $(this).removeClass('active');
        });
        $(this).addClass("active");
        $("#cover-img").css('background-image', 'url("'+$(this).attr('src')+'")');
    });

});