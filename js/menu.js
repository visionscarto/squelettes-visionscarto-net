var pos_gauche_init = 0;
var pos_gauche = 0;
var margin_left_init = 0;

$(document).ready(function() {
	$("#menu").on("touchstart MSPointerDown pointerdown", function(e) {
	
		var event = e.originalEvent;
		
		if (event.pointerType && (event.pointerType == event.MSPOINTER_TYPE_MOUSE || event.pointerType == "mouse" )) return;

		pos_gauche_init = event.pageX || event.targetTouches[0].pageX;
				
		pos_gauche = pos_gauche_init;
		margin_left_init = 0;
		
		$("#menu").addClass("notrans");
	});
	$("#menu").on("touchmove MSPointerMove pointermove", function(e) {
		var event = e.originalEvent;
		if (event.pointerType && (event.pointerType == event.MSPOINTER_TYPE_MOUSE || event.pointerType == "mouse" )) return;
		event.preventDefault();
		pos_gauche = event.pageX || event.targetTouches[0].pageX;
		var decal = pos_gauche - pos_gauche_init;
		
		if (decal > 0) {
			$("#menu").css("transform", "translate("+ decal +"px, 0)");
		}
		
	});
	$("#menu").on("touchend MSPointerUp pointerup", function(e) {
		var event = e.originalEvent;
		if (event.pointerType && (event.pointerType == event.MSPOINTER_TYPE_MOUSE || event.pointerType == "mouse" )) return;

		$("#menu").removeClass("notrans");
		$("#menu").css("transform", "");

		if (pos_gauche > pos_gauche_init - 50) {
			$("#activer_menu").prop("checked", false);
		} else {
			$("#activer_menu").prop("checked", true);
		}
	});

});