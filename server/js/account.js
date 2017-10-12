$(document).ready(function() {
	$("#edit-ad").click(function(){
		$("#no-edit-ad").css({display: "none"});
		$("#ad-edit").css({display: "block"});
	});
	$("#edit-pers").click(function(){
		$("#no-edit-pers").css({display: "none"});
		$("#pers-edit").css({display: "block"});
	});
	$("#edit-pay").click(function(){
		$("#no-edit-pay").css({display: "none"});
		$("#pay-edit").css({display: "block"});
	});
});