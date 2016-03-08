<?php
	require_once('DbAccessor.php');
	require_once('TokenManager.php');
	
	// continue session
	session_start(); 
	
	$tokenManager = new TokenManager();
	$validToken = $tokenManager->verifyToken('form1');
	
	
	if ($validToken) {
	
		$dbAccessor = new DbAccessor();
		
		// get post parameters
		$user = $_POST['theuser'];
		$pass = $_POST['password'];
		$doodle = $_POST['doodle'];
		
		
		// add user
		$success = false;
		$userExists = $dbAccessor->userExists($user);
		if (!$userExists) {
			$success = $dbAccessor->addUser($user, $pass, $doodle);
			//$success = true;
		}
		

	}
	

if ($validToken) : ?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="doodleStyle.css">
	</head>
	<body>
		<div class="liteBox">
			<?php 
			// TITLE
			if ($userExists or !$success) {
				echo "<h1>Error</h1>";
			}	
			else if ($success) {
				echo "<h1>Success</h1>";
			}
			?>
		</div>
		<div class="liteBox">
			<?php
			// DESCRIPTION
			echo "<p>";
			if ($userExists) {
				echo "An account already exists with that email address.";
			} else if (!$success) {
				echo "An error occurred with submitting your information to the database. Please contact mpl934@mocs.utc.edu for help.";
			} else if ($success) {
				echo "Congratulations! You have successfully registered!<br>You will be contacted to login using the same credentials. Please do not write them down.";
			}
			echo "</p>";
			?>
		</div>
	</body>
</html>

<?php endif; ?>