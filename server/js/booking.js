$(document).ready(function() {
	$("#booking-next-2").click(function(){
		$("#booking-step-1").css({display: "none"});
		$("#booking-step-2").css({display: "block"});
		$("#booking-step-3").css({display: "none"});
		$("#formInstructions").text("Please Verify Your Billing Information");
		$("#span-tag").text("Booking Request 2/3");
	});
	$("#booking-next-3").click(function(){
		$("#booking-step-1").css({display: "none"});
		$("#booking-step-2").css({display: "none"});
		$("#booking-step-3").css({display: "block"});
		$("#formInstructions").text("Confirm your payment details");
		$("#span-tag").text("Booking Request 3/3");
	});
	$("#booking-previous-2").click(function(){
		$("#booking-step-1").css({display: "none"});
		$("#booking-step-2").css({display: "block"});
		$("#booking-step-3").css({display: "none"});
		$("#formInstructions").text("Please Verify Your Billing Information");
		$("#span-tag").text("Booking Request 2/3");
	});
	$("#booking-previous-1").click(function(){
		$("#booking-step-1").css({display: "block"});
		$("#booking-step-2").css({display: "none"});
		$("#booking-step-3").css({display: "none"});
		$("#formInstructions").text("Select your desired timeframe of booking");
		$("#span-tag").text("Booking Request 1/3");
	});
});


$(".cancel-booking").click(function(e) {
	e.preventDefault();
	if(confirm("Are you sure you want to cancel your booking?")) {
		var id = $(".cancel-booking").data("id");
		window.location.replace('bookingCancelSubmitted.php?bookingid='+id);
	}
})