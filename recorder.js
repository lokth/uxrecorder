var mousePos, 
	counter = 0,
	xc,	yc, key, time
	data = [];

// function seeRecording(){
// strdata = JSON.stringify(data);
// var siteurl = window.location.href;
// $.ajax({
// url: '../uxrecorder/postdata.php',
// type: 'POST',
// data: {rec_data: strdata, siteurl: siteurl},
// complete: function(msg) {
// console.log(msg);
// }
// });
// }

$(document).ready(function(e) {
	window.onmousemove = handleMouseMove;
	window.onmousedown = handleMouseClick;
	window.onscroll = handleMouseScroll;
	window.onkeyup = handlekeystrokes;

	setInterval(getMousePosition, 100);
	// setInterval repeats every X ms

	var observer = new MutationObserver(function(mutationRecords) {
		content = mutationRecords[0].target.innerHTML;
		content = content.replace(/\n\r?/g, '<br />');
		content = content.replace(/\t?/g, '');
		content = content.replace(/onclick=".*?"/g, '');

		content = stripScripts(content);
		content = content.replace(/'/g, "\'");
		//content = addslashes(JSON.stringify(content));
		mousePos = {
			ev : "mutationevent",
			x : mutationRecords[0].target.id,
			y : content,
			t : counter
		};
		data.push(mousePos);
		//console.log(mutationRecords[0].target.id);
		//console.log(mutationRecords[0].target.innerHTML);
	});

	observer.observe(document, {// options:
		subtree : true, // observe the subtree rooted at myNode
		childList : true, // include information childNode insertion/removals
		attribute : true // include information about changes to attributes within the subtree
	});

	function addslashes(str) {
		return (str + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
	}

	function stripScripts(s) {
		var div = document.createElement('div');
		div.innerHTML = s;
		var scripts = div.getElementsByTagName('script');
		var i = scripts.length;
		while (i--) {
			scripts[i].parentNode.removeChild(scripts[i]);
		}
		return div.innerHTML;
	}

	//********* checkbox tracking start **********//

	// $("input:checkbox")
	// //.each(addToChecked)
	// .on("click", addToToggleChecked);

	function addToToggleChecked() {
		eid = this.id;
		if ($(this).is(":checked")) {
			mousePos = {
				//x is element id, y is checkbox value
				ev : "checkbox",
				x : eid,
				y : 1,
				t : counter
			};
		} else {
			mousePos = {
				//x is element id, y is checkbox value
				ev : "checkbox",
				x : eid,
				y : 0,
				t : counter
			};
		}
		data.push(mousePos);
	}

	// tracking  on document load if checkbox is checked or not
	// function addToChecked() {
	// eid = this.id;
	// if ($(this).is(":checked")) {
	// mousePos = {
	// //x is element id, y is checkbox value
	// ev: "checkbox",
	// x: eid,
	// y: 1,
	// t: counter
	// };
	// }
	// data.push(mousePos);
	// }

	//********* checkbox tracking end **********//

	//********* radio button tracking start **********//
	// $("input:radio")
	// //.each(addToChecked)
	// .on("click", addToToggleRadio);

	function addToToggleRadio() {
		eid = this.id;
		if ($(this).is(":checked")) {
			mousePos = {
				//x is element id, y is checkbox value
				ev : "radio",
				x : eid,
				y : 1,
				t : counter
			};
		} else {
			mousePos = {
				//x is element id, y is checkbox value
				ev : "radio",
				x : eid,
				y : 0,
				t : counter
			};
		}
		data.push(mousePos);
	}

	//********* radio button tracking end **********//

	function handleMouseMove(event) {
		event = event || window.event;
		// IE-ism

		xc = event.clientX;
		yc = event.clientY;
	}

	function handleCursorposition() {
		event = event || window.event;
		// IE-ism
		var yp = window.pageYOffset || document.documentElement.scrollTop;
		var xp = window.pageXOffset || document.documentElement.scrollLeft

		xcp = event.clientX + xp;
		ycp = event.clientY + yp;
	}

	function getMousePosition() {
		if (!xc && !yc) {
			// We haven't seen any movement yet
			mousePos = {
				ev : "?",
				x : "?",
				y : "?",
				t : counter
			};
		} else {
			var yp = window.pageYOffset || document.documentElement.scrollTop;
			var xp = window.pageXOffset || document.documentElement.scrollLeft
			mousePos = {
				ev : "mousemove",
				x : xc + xp,
				y : yc + yp,
				t : counter
			};
			data.push(mousePos);
		}
		//console.log(mousePos);
		++counter;
	}

	function handleMouseClick() {
		event = event || window.event;
		var yp = window.pageYOffset || document.documentElement.scrollTop;
		var xp = window.pageXOffset || document.documentElement.scrollLeft;

		mousePos = {
			ev : "click",
			x : xc + xp,
			y : yc + yp,
			t : counter
		};
		data.push(mousePos);
	}

	function handleMouseScroll() {
		var top = window.pageYOffset || document.documentElement.scrollTop;
		var left = window.pageXOffset || document.documentElement.scrollLeft;
		mousePos = {
			ev : "scroll",
			x : left,
			y : top,
			t : counter
		};
		data.push(mousePos);
		//console.log(data);
	}

	function handlekeystrokes() {
		element = document.activeElement;
		elementid = element.id;
		// $('#'+elementid).keypress(
		// function(e) {
		// time = e.timeStamp;
		// }
		// );
		key = $('#' + elementid).val();
		key = key.replace(/\n\r?/g, '<br />');
		mousePos = {
			ev : "keystroke",
			x : elementid,
			y : key,
			// time: time,
			t : counter
		};
		data.push(mousePos);

	}
	
		window.onbeforeunload = function() {
			var siteurl = window.location.href;
			strdata = JSON.stringify(data);
			var siteurl = window.location.href;
			$.ajax({
				url : '../uxrecorder/postdata.php',
				type : 'POST',
				async:false,
				data : {
					rec_data : strdata,
					siteurl : siteurl
				},
				complete : function(msg) {
					console.log(msg);
				}
			});
		}
	

});





