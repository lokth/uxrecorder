window.onload = function() {

	var mouse = '<div id="mf-pointer" style="position: absolute; top: 0px;left: 0px; z-index: 1000; display: block;"><div style="width:17px;height:22px" id="mf12"><img id="mf-pointer-image" src="http://localhost/uxcamrecorder/images/hand.png" style="position:relative;"></div></div>	';

	var iframes = document.getElementsByTagName('iframe');
	var iframeid = document.getElementById('iframe1');
	for (var i = 0, len = iframes.length, doc; i < len; ++i) {
		doc = iframes[i].contentDocument || iframes[i].contentWindow.document;
		// doc.body.style.cursor = "none";
		// doc.designMode = "on";
	}

	bodytag = $("#iframe1").contents().find("body");
	$(mouse).prependTo(bodytag);

	//iframeid.height = doc.body.scrollHeight + "px";

	var data = '<?php echo $data; ?>';
	//console.log(data);

	data = $.parseJSON(data);

	$.each(data, function(indexInArray) {
		setTimeout(function() {
			playback(data[indexInArray]);
		}, indexInArray * 100);
	});

	function playback(data) {
		var ev = data.ev;
		var x = data.x;
		var y = data.y;
		var t = data.t;

		$("#iframe1").contents().find("#mf-pointer").css("left", x + "px");
		$("#iframe1").contents().find("#mf-pointer").css("top", y + "px");

		if (ev == 'checkbox') {
			//x is elementid , y is checked or not flag
			simulateTogglechecked(x, y);
		}

		if (ev == 'radio') {
			//x is elementid , y is checked or not flag
			simulateToggleradio(x, y);
		}

		if (ev == 'click') {
			simulateClick(x, y, t);
		}

		if (ev == 'scroll') {
			simulateScroll(x, y);
		}

		if (ev == 'mutationevent') {
			simulateMutation(x, y);
		}

		if (ev == 'keystroke') {
			//x is elementid , y is key
			simulateKeystrokes(x, y);
		}
	}

	function simulateMutation(x, y) {
		var regex = /<br\s*[\/]?>/gi;
		//y = y.replace(regex, "\n");
		element = $("#iframe1").contents().find("#" + x);
		$(element).html(unescape(y));
	}

	function simulateTogglechecked(x, y) {
		//x is elementid , y is checked or not flag
		element = $("#iframe1").contents().find("#" + x);
		if (y == 1) {
			element.prop('checked', true);
		} else {
			element.prop('checked', false);
		}
	}

	function simulateToggleradio(x, y) {
		//x is elementid , y is checked or not flag
		element = $("#iframe1").contents().find("#" + x);
		if (y == 1) {
			element.prop('checked', true);
		} else {
			element.prop('checked', false);
		}
	}

	function simulateClick(x, y, t) {
		$("<div class='trace" + t + "' style='border-radius: 50%;width: 10px;height: 10px;background-color: rgba(255, 185, 36, 1);position: absolute; top: 0px;left: 0px; z-index: 10001;'></div>").prependTo(bodytag);
		$("#iframe1").contents().find(".trace" + t).css("left", x + "px");
		$("#iframe1").contents().find(".trace" + t).css("top", y + "px");
	}

	function simulateScroll(x, y) {
		iframeid.contentWindow.scrollTo(x, y);
	}

	function simulateKeystrokes(x, y) {
		//$("#iframe1").contents().find("#mf-pointer").css("display","none");
		//x is elementid , y is key
		var regex = /<br\s*[\/]?>/gi;
		y = y.replace(regex, "\n");
		elementc = $("#iframe1").contents().find("#" + x);
		elementc.focus().val(y);
	}

	// get mouse position inside iframe
	// $(doc).bind("mousemove", function(e) {
	// $("#result").html("x:" + (e.pageX) + ", y:" + e.pageY);
	// });

	// function update(e) {
	// $("#mf-pointer").css("left", e.pageX + "px");
	// $("#mf-pointer").css("top", e.pageY  + "px");
	// }

};

//get mouse position inside parent (main ) window
// $(document).bind("mousemove", function(e) {
// $("#result").html("x:" + e.pageX + ", y:" + e.pageY);
// });
