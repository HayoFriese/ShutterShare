$(document).ready(function() {
	$("#step-2").click(function(){
		if($("input[name='username']").val() != "" && $("input[name='email']").val() != "" && $("input[name='password']").val() != "" && $("input[name='confirmPassword']").val() != ""){
			
			$("input[name='username']").css('border', '1px solid #797979');
			$("input[name='password']").css('border', '1px solid #797979');
			$("input[name='confirmPassword']").css('border', '1px solid #797979');
			$("input[name='email']").css('border', '1px solid #797979');

			$("#register-part1").css({display: "none"});
			$("#register-part2").css({display: "block"});
			$("#register-part3").css({display: "none"});
			$("#register-part4").css({display: "none"});
			$("#error-tag").text("");
			$(".sign-in-register-title > h1").text("Complete Your Account");

		} else if($("input[name='username']").val() == ""){

			$("#error-tag").text("Please fill out all required details");
			$("input[name='username']").css('border', '2px solid red');
			$("input[name='email']").css('border', '1px solid #797979');
			$("input[name='password']").css('border', '1px solid #797979');
			$("input[name='confirmPassword']").css('border', '1px solid #797979');

		} else if($("input[name='email']").val() == ""){

			$("#error-tag").text("Please fill out all required details");
			$("input[name='email']").css('border', '2px solid red');
			$("input[name='username']").css('border', '1px solid #797979');
			$("input[name='password']").css('border', '1px solid #797979');
			$("input[name='confirmPassword']").css('border', '1px solid #797979');

		} else if($("input[name='password']").val() == ""){

			$("#error-tag").text("Please fill out all required details");
			$("input[name='password']").css('border', '2px solid red');
			$("input[name='username']").css('border', '1px solid #797979');
			$("input[name='email']").css('border', '1px solid #797979');
			$("input[name='confirmPassword']").css('border', '1px solid #797979');

		} else if($("input[name='confirmPassword']").val() == ""){

			$("#error-tag").text("Please fill out all required details");
			$("input[name='confirmPassword']").css('border', '2px solid red');
			$("input[name='username']").css('border', '1px solid #797979');
			$("input[name='password']").css('border', '1px solid #797979');
			$("input[name='email']").css('border', '1px solid #797979');

		}
	});
	$("#step-3").click(function(){
		if($("input[name='fname']").val() != "" && $("input[name='surname']").val() != ""){

			$("input[name='fname']").css('border', '1px solid #797979');
			$("input[name='surname']").css('border', '1px solid #797979');

			$("#register-part1").css({display: "none"});
			$("#register-part2").css({display: "none"});
			$("#register-part3").css({display: "block"});
			$("#register-part4").css({display: "none"});
			$("#error-tag").text("");
			$(".sign-in-register-title > h1").text("Billing Address");

		} else if($("input[name='fname']").val() == ""){

			$("#error-tag").text("Please fill out all required details");
			$("input[name='fname']").css('border', '2px solid red');
			$("input[name='surname']").css('border', '1px solid #797979');

		} else if($("input[name='surname']").val() == ""){

			$("#error-tag").text("Please fill out all required details");
			$("input[name='surname']").css('border', '2px solid red');
			$("input[name='fname']").css('border', '1px solid #797979');

		}
	});
	$("#step-4").click(function(){
		if($("input[name='addline1']").val() != "" && $("input[name='city']").val() != "" && $("input[name='zipcode']").val() != "" && $("input[name='region']").val() != "" && $("input[name='country']").val() != ""){

			$("input[name='addline1']").css('border', '1px solid #797979');
			$("input[name='city']").css('border', '1px solid #797979');
			$("input[name='zipcode']").css('border', '1px solid #797979');
			$("input[name='region']").css('border', '1px solid #797979');
			$("input[name='country']").css('border', '1px solid #797979');

			$("#register-part1").css({display: "none"});
			$("#register-part2").css({display: "none"});
			$("#register-part3").css({display: "none"});
			$("#register-part4").css({display: "block"});
			$("#error-tag").text("");
			$(".sign-in-register-title > h1").text("Security Question");

		} else if($("input[name='addline1']").val() == ""){

			$("#error-tag").text("Please fill out all required details");
			$("input[name='addline1']").css('border', '2px solid red');
			$("input[name='city']").css('border', '1px solid #797979');
			$("input[name='zipcode']").css('border', '1px solid #797979');
			$("input[name='region']").css('border', '1px solid #797979');
			$("input[name='country']").css('border', '1px solid #797979');

		} else if($("input[name='city']").val() == ""){

			$("#error-tag").text("Please fill out all required details");
			$("input[name='city']").css('border', '2px solid red');
			$("input[name='addline1']").css('border', '1px solid #797979');
			$("input[name='zipcode']").css('border', '1px solid #797979');
			$("input[name='region']").css('border', '1px solid #797979');
			$("input[name='country']").css('border', '1px solid #797979');

		} else if($("input[name='zipcode']").val() == ""){

			$("#error-tag").text("Please fill out all required details");
			$("input[name='zipcode']").css('border', '2px solid red');
			$("input[name='addline1']").css('border', '1px solid #797979');
			$("input[name='city']").css('border', '1px solid #797979');
			$("input[name='region']").css('border', '1px solid #797979');
			$("input[name='country']").css('border', '1px solid #797979');

		} else if($("input[name='region']").val() == ""){

			$("#error-tag").text("Please fill out all required details");
			$("input[name='region']").css('border', '2px solid red');
			$("input[name='addline1']").css('border', '1px solid #797979');
			$("input[name='city']").css('border', '1px solid #797979');
			$("input[name='zipcode']").css('border', '1px solid #797979');
			$("input[name='country']").css('border', '1px solid #797979');

		} else if($("input[name='country']").val() == ""){

			$("#error-tag").text("Please fill out all required details");
			$("input[name='country']").css('border', '2px solid red');
			$("input[name='addline1']").css('border', '1px solid #797979');
			$("input[name='city']").css('border', '1px solid #797979');
			$("input[name='zipcode']").css('border', '1px solid #797979');
			$("input[name='region']").css('border', '1px solid #797979');

		}
	});
	$("#step-to-1").click(function(){
		$("#register-part1").css({display: "block"});
		$("#register-part2").css({display: "none"});
		$("#register-part3").css({display: "none"});
		$("#register-part4").css({display: "none"});
		$("#error-tag").text("");
		$(".sign-in-register-title > h1").html("Shutter<span id='share'>Share</span>");
	});
	$("#step-to-2").click(function(){
		$("#register-part1").css({display: "none"});
		$("#register-part2").css({display: "block"});
		$("#register-part3").css({display: "none"});
		$("#register-part4").css({display: "none"});
		$("#error-tag").text("");
		$(".sign-in-register-title > h1").text("Complete Your Account");
	});
		$("#step-to-3").click(function(){
		$("#register-part1").css({display: "none"});
		$("#register-part2").css({display: "none"});
		$("#register-part3").css({display: "block"});
		$("#register-part4").css({display: "none"});
		$("#error-tag").text("");
		$(".sign-in-register-title > h1").text("Billing Address");
	});

});