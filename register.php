<?php
// reference: https://css-tricks.com/serious-form-security/
// extra: https://www.owasp.org/index.php/PHP_CSRF_Guard

require_once('TokenManager.php');

session_start();

$tokenManager = new TokenManager();

$newToken = $tokenManager->generateFormToken('form1');


?>


<!DOCTYPE html>
<html>

	<head>
		<title>Doodle Password</title>
		<link rel="stylesheet" type="text/css" href="doodleStyle.css">
		<script src="paper-full.min.js"></script>
		<script src="zxcvbn.js"></script>

		<script src="preDoodleScript.js"></script>
		<script src="doodleScript.js" type="text/paperscript" canvas="canvas"></script>
		

		
	</head>
	<body>
		
		<div class="blue left"><img class="titleImg" src="doodle.svg"></div>
		<h1 class="center white titleTxt">VS</h1>
		<div class="red right"><img class="titleImg" src="password.svg"></div>

		
		<form id="loginForm" class="clear" method="post">
			<input type="hidden" name="token" value="<?php echo $newToken;?>">
			<div class="liteBox">
				<h1>Registration</h1>
				<h2>Enter your username / email</h2>
				<input name="theuser" type="text" required>
			</div>
			
			<div id="doodleSection" class="wide blue">
				<h2>Enter your doodle</h2>
				
				<input id="doodleInput" name="doodle" type="hidden">
			
				<div id="container">
					<img id="passPic" draggable="false" src="background.jpg"/>
					<canvas id="canvas"></canvas>
				</div>
				
				<button onClick="resetDoodle();" type="button">Reset</button>
				<button onClick="undoStroke();" type="button">Undo Last Line</button>
				
			</div>
			<div class="wide red">
				<div id="password-container">
					<h2>Enter your password</h2>
					<h3>Required Strength: Good</h3>
					
					<input id="password" name="password" type="password" required><img id="enter-pass-img" src="x-mark.png">
					<meter max="4" id="password-strength-meter"></meter>
					<p id="password-strength-text"></p>
					
					<h2>Confirm Password</h2>
					<input name="passCheck" type="password">
				</div>
			</div>
			
			<div class="liteBox">
				<button type="button" onclick="register();">Register</button>
			</div>
			
		</form>
	</body>
	
	<script>
			var password = document.getElementById('password');
			var meter = document.getElementById('password-strength-meter');
			var text = document.getElementById('password-strength-text');
			
			var strength = {
			  0: "Worst",
			  1: "Bad",
			  2: "Weak",
			  3: "Good",
			  4: "Strong"
			}

			password.addEventListener('input', function() {
				
				var val = password.value;
				var result = zxcvbn(val);

				// Update the password strength meter
				meter.value = result.score;
				

				// Update the text indicator
				if (val !== "") {
					text.innerHTML = "Strength: " + strength[result.score]; 
				} else {
					text.innerHTML = "";
				}
			});
	</script>
</html>

<!--
<!DOCTYPE html>
<html>
<head>
<style>
.container {
    position: relative;
    background-color: yellow;
}

.topright {
    position: absolute;
    top: 0px;
    right: 0px;
    font-size: 18px;
    background-color: #0000FF;
    width: 100%;
    height:100%;
    z-index: -5
}

img { 
    width: 100%;
    height: auto;
    opacity: 0.3;
}
</style>
</head>
<body>

<h2>Image Text</h2>
<p>Add some text to an image in the top right corner:</p>

<div class="container">
  <img src="trolltunga.jpg" alt="Norway" width="100" height="300">
  <div class="topright">Top Right</div>
</div>

</body>
</html>
-->