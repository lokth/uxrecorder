$(document).ready(function() {
	var Url = window.location.href;
	//console.log(Url);
	var doc = $('html').prop('outerHTML');
	$.ajax({
		url : "../uxrecorder/core.php",
		type : "POST",
		dataType : "html",
		data : {
			'doc' : doc,
			'myurl' : Url
		},
		success : function(data) {
			//$('#content').html(data);
			console.log(data);
		}
	});
	return false;
});

