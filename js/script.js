$(function() {
	$.get('?action=opus_importer', {}, function(c) {
		console.log(c);
	});
});