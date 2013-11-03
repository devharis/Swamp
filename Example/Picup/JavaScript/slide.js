$(document).ready(function() {
	
	if ($(".ui-state-error").length > 0 || $(".OpenPanel").length > 0) {
		$("div#panel").slideDown("fast");		
		$("#dimmer").show();
		$("#dimmer").animate({
			opacity: 0.75
		}, 500);
		$("#toggle a").toggle();
	}
	
	
	$(".openPanel").click(function() {
		$("#open").click();
	});
		
	// Expand Panel
	$("#open").click(function() {			
		$("div#panel").slideDown("easeOutBounce");
		
		$("#dimmer").show();
		$("#dimmer").animate({
			opacity: 0.75
		}, 500);
		
	});	
	
	$("#dimmer").click(function() {
		$("#close").click();
	})
	
	// Collapse Panel
	$("#close").click(function(){
		$("div#panel").slideUp("easeOutBounce");	
		$("#dimmer").animate({
			opacity: 0
		}, 500, function(){
			$("#dimmer").hide();
		});
	});		
	
	// Switch buttons from "Log In | Register" to "Close Panel" on click
	$("#toggle a").click(function () {
		$("#toggle a").toggle();
	});		
		
});