<?php
	require_once('DbAccessor.php');
	require_once('TokenManager.php');
	
	// continue session
	session_start(); 
	
	$tokenManager = new TokenManager();
	$validToken = $tokenManager->verifyToken('form1')
	
	if ($validToken) {
	
		$dbAccessor = new DbAccessor();
		
		// get post parameters
		
		
		// add user
		$success = false;
		if (!$dbAccessor->userExists('test')) {
			$success = $dbAccessor->addUser('test', 'wowser', "{}");
		}
		else {
			echo "User already exists<br>";
		}
		
		
		if ($success) {
			echo "Test user successfully added.";
		} else {
			echo "Failed to add test user.";
		}
	}
	

if ($validToken) : ?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="doodleStyle.css">
	</head>
	<body>
		
	</body>
</html>

<?php endif; ?>