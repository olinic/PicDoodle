<?php
/*
	Responds to AJAX requests with JSON.
	Accepts POST parameters
		d1 - first doodle
		d2 - second doodle

	Tokens are used to ensure that requests only come from the website
*/
	require_once('TokenManager.php');
	require_once('DoodleWorker.php');

	// continue session
	session_start();


	$tokenManager = new TokenManager();
	$validToken = $tokenManager->verifyToken('verify1');

	if ($validToken) {

		$dWorker = new DoodleWorker();

		// get post parameters
		$d1 = $_POST['d1'];
		$d2 = $_POST['d2'];

		// get the success
		$success = $dWorker->verifyDoodle($d1, $d2);

		// get the details
		$details = $dWorker->doodleDetails();

		// compile it for JSON
		$details["success"] = $success;

		// echo it out
		echo json_encode($details);


	} else {
		// report invalid
		echo '{"error": "Could not verify doodles. Invalid token."}';
	}
?>
