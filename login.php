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
		
		<div id="titleBar">
			<div class="blue left"><img class="titleImg" src="doodle.svg"></div>
			<span id="vs">VS</span>
			<div class="red right"><img class="titleImg" src="password.svg"></div>
		</div>
		
		<div id="inside">
			<form id="loginForm" class="clear" method="post">
				<input type="hidden" name="token" value="<?php echo $newToken;?>">
				<div class="liteBox">
					<h1>Login</h1>
					<h2>Enter your username / email</h2>
					<input id="email" name="theuser" type="text" value="<?php if (isset($_GET['user'])) echo $_GET['user']; ?>" required>
				</div>
				
				<div id="doodleSection" class="wide blue">
					<h2>Enter your doodle</h2>
					
					<input id="doodleInput" name="doodle" type="hidden">
				
					<div id="container">
						<img id="passPic" draggable="false" src="background.jpg"/>
						<canvas id="canvas"></canvas>
					</div>
					
					<button onClick="resetDoodle();" type="button">Reset</button>
					<br>
					<button onClick="undoStroke();" type="button">Undo Last Line</button>
					
				</div>
				<div class="wide red">
					<div id="password-container">
						<h2>Enter your password</h2>
						<input id="password" name="password" type="password" required>
					</div>
				</div>
				
				<div class="liteBox">
					<p id="error-dialog"></p>
					<button type="button" onclick="login();">Login</button>
				</div>
				
			</form>
		</div>
	</body>
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