$(document).ready(function() {

  function data(id){
    var data = id.text();
    return data;
  }

  function data2(id){
    var data2 = id.value;
    return data2;
  }

  $("#dropdown li").click(function(){
    var text = data($(this));

    if(text == "All"){
      $("#admins").css({display: "block"});
      $("#create").css({display: "none"});
      $("#unsuspend").css({display: "none"});
      $("#delete").css({display: "none"});
      $("#userLink").css({display: "none"});
    }

    if(text == "Suspend"){
      $("#userLink").css({display: "inline-block"});
      $("#users").css({display: "none"});
      $("#create").css({display: "block"});
      $("#unsuspend").css({display: "none"});
      $("#delete").css({display: "none"});
    }

    if(text == "Unsuspend"){
      $("#userLink").css({display: "inline-block"});
      $("#users").css({display: "none"});
      $("#suspend").css({display: "none"});
      $("#unsuspend").css({display: "block"});
      $("#delete").css({display: "none"});
    }

    if(text == "Create"){
      $("#admins").css({display: "none"});
      $("#users").css({display: "none"});
      $("#create").css({display: "block"});
      $("#unsuspend").css({display: "none"});
      $("#delete").css({display: "none"});
    }

    if(text == "Deactivate" || text == "Delete"){
      $("#userLink").css({display: "inline-block"});
      $("#admins").css({display: "none"});
      $("#users").css({display: "none"});
      $("#create").css({display: "none"});
      $("#unsuspend").css({display: "none"});
      $("#adverts").css({display: "none"});
      $("#delete").css({display: "block"});
    }

    if(text == "Reactivate"){
      $("#userLink").css({display: "inline-block"});
      $("#admins").css({display: "none"});
      $("#users").css({display: "none"});
      $("#create").css({display: "none"});
      $("#unsuspend").css({display: "none"});
      $("#adverts").css({display: "none"});
      $("#delete").css({display: "none"});
      $("#restore").css({display: "block"});
    }

    if(text == "Restore"){
      $("#userLink").css({display: "inline-block"});
      $("#admins").css({display: "none"});
      $("#users").css({display: "none"});
      $("#create").css({display: "none"});
      $("#unsuspend").css({display: "none"});
      $("#adverts").css({display: "none"});
      $("#delete").css({display: "none"});
      $("#restore").css({display: "block"});
    }

    if(text == "Resolve"){
      $("#userLink").css({display: "inline-block"});
      $("#admins").css({display: "none"});
      $("#users").css({display: "none"});
      $("#create").css({display: "none"});
      $("#unsuspend").css({display: "none"});
      $("#adverts").css({display: "none"});
      $("#reports").css({display: "none"});
      $("#delete").css({display: "none"});
      $("#restore").css({display: "none"});
      $("#resolve").css({display: "block"});
    }

    if(text == "Reports"){
      $("#userLink").css({display: "inline-block"});
      $("#advertReports").css({display: "block"});
      $("#adverts").css({display: "none"});
      $("#delete").css({display: "none"});
      $("#users").css({display: "none"});
    }

    if(text == "Delete"){
      $("#advertReports").css({display: "none"});
      $("#adverts").css({display: "none"});
      $("#delete").css({display: "block"});
    }



    //if (text of breadcrumb dropdown link == "A Certain Text"){
    //css of first div = display something
    //css other divs .....
    //}

    $("#breadcrumb > div > ul > li > span > a").text(text);
  });

  $("#create-next").click(function (){
    $("#createStep1").css({display: "none"});
    $("#createStep2").css({display: "block"});
    $("#changeKey").focus();
  });

});

function changeSubmit(id) {

  var text = id.value;

  if (text == 'select' || text == 'none' || text == 'deleteAdvert' || text == 'deleteReview') {
    $("#suspend").css({display: "none"});
    $("#delete").css({display: "none"});
  }

  if (text == 'none') {
    document.getElementById('confirm').name = "resolveReportDismiss";
  } else if (text == 'deleteAdvert') {
    document.getElementById('confirm').name = "resolveReportDeleteAdvert";
  } else if (text == 'deleteReview') {
    document.getElementById('confirm').name = "resolveReportDeleteReview";
  } else if (text == 'suspend') {
    $("#suspend").css({display: "block"});
    $("#delete").css({display: "none"});
    document.getElementById('confirm').name = "resolveReportSuspendUser";
  } else if (text == 'delete') {
    $("#suspend").css({display: "none"});
    $("#delete").css({display: "block"});
    document.getElementById('confirm').name = "resolveReportDeleteUser";
  }

}