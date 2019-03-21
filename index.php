<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type"
		content="text/html; charset=iso-8859-1">
		<title>UxcamRecorder Replay View </title>
		<link href="css/style.css" rel="stylesheet" type="text/css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<!-- 		<script src="player.js"></script> -->
		<style>
			.restricted {
				overflow-y: hidden;
			}
		</style>
	</head>
	<body>
		<?php // $file = "data.json";
		// $handle = fopen($file, 'r');
		// $data = fread($handle, filesize($file));

		$hostname = "localhost";
		$username = "root";
		$password = "";
		$con = mysqli_connect($hostname, $username, $password, "uxrecorder");

		$result = mysqli_query($con, "SELECT * FROM tbl_sitedata");
		while ($row = mysqli_fetch_array($result)) {
			$site_url[][$row['url']] = $row['data_json'];
		}
		foreach ($site_url[0] as $key => $value) {
			$firsturl = $key;
		}

		mysqli_close($con);
		?>
		
		
		<div id="dropdown">
			
			Select website url: 
				<select id="selectsite">
					<?php 
					foreach ($site_url as $key => $value) {
						foreach ($value as $murl => $json) { ?> 
					  		<option value="<?php echo $key . ',' . $murl; ?>"><?php echo $murl; ?></option>			 
					  <?php  }
							}
 					?>
				</select>
			
		</div>
		
		<iframe id="iframe1" src="<?php echo $firsturl; ?>" width="100%" class="restricted" scrolling="no" style="height: 760px;-webkit-transform: scale(0.75);">
			<p>
				Your browser does not support iframes.
			</p>
		</iframe>
	</body>
	
	<script type="text/javascript">
	
		
	window.onload = function() {

		$('#selectsite').change(function() 
		{
			   var val = $(this).val();
			   valsplit = val.split(',');
			   key = valsplit[0];
			   url = valsplit[1];
			   jsArray = <?php echo json_encode($site_url); ?>;
			   json = jsArray[key][url];
			   
			   var iframes = document.getElementsByTagName('iframe');
				var iframeid = document.getElementById('iframe1');
					iframeid.setAttribute("src", url);

					data = JSON.parse(json);

				var inter = window.setInterval(function() {
					if (iframeid.contentWindow.document.readyState === "complete") {
						window.clearInterval(inter);
						
						// grab the content of the iframe here and do replay on the content
						
						for (var i = 0, len = iframes.length, doc; i < len; ++i) {
							doc = iframes[i].contentDocument || iframes[i].contentWindow.document;
							// doc.body.style.cursor = "none";
							// doc.designMode = "on";
						}

						bodytag = $("#iframe1").contents().find("body");
						var mouse = '<div id="mf-pointer" style="position: absolute; top: 0px;left: 0px; z-index: 10009; display: block;"><div style="width:17px;height:22px" id="mf12"><img id="mf-pointer-image" src="http://localhost/uxrecorder/img/hand.png" style="position:relative;"></div></div>';

						$(mouse).prependTo(bodytag);

						//iframeid.height = doc.body.scrollHeight + "px";

						//console.log(data);

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

					}
				}, 100);

		});

	};

				//get mouse position inside parent (main ) window
				// $(document).bind("mousemove", function(e) {
				// $("#result").html("x:" + e.pageX + ", y:" + e.pageY);
				// });

</script> 
</html>
