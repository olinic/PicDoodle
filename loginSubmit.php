<?php
	require_once('DbAccessor.php');
	require_once('DoodleWorker.php');
	require_once('TokenManager.php');
	
	// continue session
	session_start();
	
	$tokenManager = new TokenManager();
	$validToken = $tokenManager->verifyToken('form1');
	
	if ($validToken) {
	
		$dbAccessor = new DbAccessor();
		$dWorker = $dbAccessor->getWorker();
		
		// get post parameters
		$user = $_POST['theuser'];
		$pass = $_POST['password'];
		$doodle = $_POST['doodle'];
		
		// check doodle and password
		$passSuccess = $dbAccessor->authPassword($user, $pass);
		$doodleSuccess = $dbAccessor->authDoodle($user, $doodle);
		
		$details = $dWorker->doodleDetails();
		
		
		//var_dump($details);
		
		$d2 = [[1,1], [4,4], [6,6], [9,9]];
		//$d1 = [[1,1]];
		//$d2 = [[0,4], [0,3], [0,2], [0,1]];
		$d1 = [[0,0], [5,4], [7,7], [8,9], [9,11], [10, 10]];
		//$d2 = [[0,0], [1,1], [1,2]];
		//var_dump($dWorker->getDifferences($d1, $d2));
	}
	else {
		echo "Missing or Invalid token";
	}
	

if ($validToken) : ?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="doodleStyle.css">
	</head>
	<body>
		<div id="titleBar">
			<div class="blue left"><img class="titleImg" src="doodle.svg"></div>
			<span id="vs">VS</span>
			<div class="red right"><img class="titleImg" src="password.svg"></div>
		</div>
		
		<div id="inside">
	
			<div class="blue left">
				<h1>
					<?php
					// output results
					if ($doodleSuccess) {
						echo "Doodle is<br> correct";
					
					} else {
						echo "Doodle is<br> incorrect";
					}
				?>
				</h1>
			</div>
			<div class="red right">
				<h1 >
				<?php
					// output results
					if ($passSuccess) {
						echo "Password is<br> correct";
					
					} else {
						echo "Password is<br> incorrect";
					}
				?>
				</h1>
			</div>
			<form>
				<div class="liteBox">
					
					<?php 
						echo "<h1>" . $details['percentMatch'] . "% Match</h1>";
						if (!$doodleSuccess) {
							echo "<h2>What went wrong?</h2>";
							foreach ($details['errors'] as $code => $description) {
								echo "<p>$description</p>";
							}
						}
					?>
					
					
					<h2>Want to try again?</h2>
					<a href="login.php<?php echo "?user=$user"; ?>"><button type="button">Yes!</button></a>
					
					<br><br><hr><br>
					
					<h2>What did you think?</h2>
					Use my own survey or Survey Monkey?
				</div>
			</form>
		</div>
	</body>
</html>
<?php endif; ?>