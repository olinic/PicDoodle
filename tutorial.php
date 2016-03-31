<?php

require_once('TokenManager.php');

session_start();

$tokenManager = new TokenManager();

$newToken = $tokenManager->generateFormToken('verify1');

?>
<html>

	<head>
		<title>Doodle Password</title>
		<link rel="stylesheet" type="text/css" href="doodleStyle.css">

		<script>
			// handling iframe windows with doodle and AJAX

			//

			var state = 0; // 0 - save first doodle, 1 - submit doodles

			function doodleAction() {
				// clear dialog
				document.getElementById('dialog').innerText = "";
				if (state == 0) {
					// save first doodle
					// get iframe value
					var doodle = getDoodle();
					if (validDoodle(doodle)) {
						// set inside value
						var d1Item = document.getElementById('doodle1');
						d1Item.value = doodle;

						// reload iframe
						reloadIframe();

						// set state
						state = 1;

						// update button text
						updateButton(state);
					}
				} else if (state == 1) {
					// save second doodle
					var doodle = getDoodle();
					if (validDoodle(doodle)) {

						var d2Item = document.getElementById('doodle2');
						d2Item.value = doodle;

						// submit using AJAX
						verifyDoodles();

						reloadIframe();

						state = 0;
						updateButton(state);
					}
				}
			}

			function validDoodle(doodle) {
				if (doodle != '{"doodle":[]}') {
					return true;
				} else {
					// report error
					document.getElementById('dialog').innerText = "Please enter a doodle before continuing";
					return false;
				}
			}

			function updateButton(currState) {
				var button = document.getElementById('doodleAction');
				if (currState == 0) {
					button.innerText = "Save Doodle 1";
				} else if (currState == 1) {
					button.innerText = "Test Doodles";
				}
			}

			function getDoodle() {
				var iframe = document.getElementById('frame');
				var frameContent = (iframe.contentWindow || iframe.contentDocument); // returns the one that exists
				if (frameContent.document) frameContent = frameContent.document; // set to document if it exists
				var doodle = frameContent.getElementById('doodleInput').value;

				return doodle;
			}

			function reloadIframe() {
				// test in multiple browsers
				document.getElementById('frame').src += ''; // a little hack :)
			}

			function verifyDoodles() {
				// grab doodles and tokens
				var d1 = document.getElementById('doodle1').value;
				var d2 = document.getElementById('doodle2').value;
				var token = document.getElementById('token').value;

				sendRequest(d1, d2, token);
			}

			function sendRequest(d1, d2, token) {
				var req = new XMLHttpRequest();
				req.onreadystatechange = function() {
					if (req.readyState == 4 && req.status == 200) {
						onSuccess(req.responseText);
					}
				}
				var postStr = "d1=" + d1 + "&d2=" + d2 + "&token=" + token;

				req.open("POST", "verify.php", true);
				req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				req.send(postStr);
			}

			function onSuccess(response) {
				var json = JSON.parse(response);
				console.log(response)

				var dialog = document.getElementById('dialog');
				if (json.error) {
					// report error
					dialog.innerText = json.error;
				} else {
					// report details
					var html = "";
					if (json.success) {
						html += "<h3>Success</h3>";
					} else {
						html += "<h3>Failure</h3>Errors:<br>";
						for (var prop in json.errors) {
							html += json.errors[prop] + "<br>";
						}
					}
					html += json.percentMatch + "% Match";

					dialog.innerHTML = html;
				}
			}

		</script>
	</head>
	<body>

		<div id="titleBar">
			<div class="blue left"><img class="titleImg" src="doodle.svg"></div>
			<span id="vs">VS</span>
			<div class="red right"><img class="titleImg" src="password.svg"></div>
		</div>


		<div id="inside">
			<div class="liteBox">
				<h1>Tutorial</h1>
				<p>
					Every doodle is made of strokes.
					Each stroke is drawn inside the image by using your mouse or finger (for touchscreens).
					Use the following to learn more about the rules concerning doodle passwords.
				</p>

				<ol class="instructions">
					<li>Beginning and End</li>
					<p>Every stroke has a beginning and end. When logging in, you will need to start and end in the same areas to log in.</p>
					<img src="sd-Start-End.jpg">

					<li>Stroke Order</li>
					<p>
						Remember the order of your strokes!
						You will need to enter your strokes in the same order to login successfully.
					</p>
					<img src="sd-Stroke-Order.jpg">


					<li>Be Creative</li>
					<p>
						More Creativity = More Security.<br>
						For example, using more than one rotation in a circle or retracing a line back to the beginning.<br>
						You can also use <b>taps</b> (one point instead of a stroke). <br>
						Come up with your own ways to be creative!</p>
					<img src="sd-Creative-Optimized.gif">
				</ol>

			</div>

			<div class="liteBox">
				<h1>Tips</h1>
				<p>
					To create a strong doodle, try to use <b>at least three strokes</b>. <br>
					<b>Longer</b> doodles are more secure.<br>
					If you use taps, try to include more than three taps/strokes to make the doodle stronger. <br>
				</p>
			</div>


			<div class="liteBox">
				<h1> Practice </h1>
				<ol>
					<li>Create your Doodle Password (PassDoodle) in the image below</li>
					<li>Click "Save Doodle 1"</li>
					<li>Recreate the doodle</li>
					<li>Click "Test Doodles" to view the results.</li>
				</ol>

				<p id="dialog"></p>

			  <button id="doodleAction" type="button" onclick="doodleAction();">Save Doodle 1</button>
				<iframe id="frame" class="container" scrolling="no" src="sampleDoodle.html">
				</iframe>
			</div>

			<div class="liteBox">
				<a href="index.php"><button type="button">Done with Tutorial</button></a>
			</div>

			<form>
				<input id="doodle1"  type="hidden">
				<input id="doodle2" type="hidden">
				<input id="token" type="hidden" value="<?php echo $newToken;?>">
			</form>

		</div>
	</body>
</html>
