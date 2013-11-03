$(document).ready(function() {
	
	setListWidth();
	displayImageDescription();
	
	if($(".Comments").length > 0) {
		$(".Comments").animate({ scrollTop: $('.Comments')[0].scrollHeight}, 1000);		
	}
	
	if ($("#ViewImage")) {
		$("#ViewImage").aeImageResize({height: 525, width: 700});
	}
	
	$(".EditDescForm").hide();
	$(".ShowEditForm").click(function () {		
		$(".EditDescForm").toggle();
		$(".EditDescInput").focus().focus(function () {
			this.select();
		});		
		$("#ImageHeader").toggle();
	});
	
	$(".EditUserForm").hide();
	$(".ShowEditUserForm").click(function () {
		$(".EditUserForm").slideToggle(100);
	});
	
	
	$("#textarea").focus(function () {
		$("#textarea").animate({height: "80"}, 500);
		 $(this).keyup(function(){
                var max = 140;
                var len = $(this).val().length;
                if (len >= max) {
                    $('#charNum').text(' you have reached the limit');
                } else {
                    var char = max - len;
                    $('#charNum').text( char + ' characters left');
                }
	    });
	});
	
	$("#textarea").blur(function () {
		$("#textarea").animate({height: "20"}, 500);		
	});
	
	function setListWidth() {
		var totalImageSize = null;
		var images = $("#horiz_container").children();
		
		totalImageSize = 430 * $(images).length;
		
		$("#horiz_container").width(totalImageSize);		
	}
	
	$("#horiz_container_outer").horizontalScroll();
	
	function displayImageDescription() {		
		$(".imageDiv").hover(function () {$(this).children().addClass("imageDivHover")}, 
								function () {$(this).children().removeClass("imageDivHover", 300)});
	}
})
