<?php
class TokenManager {
	
	function generateFormToken($form) {
		// generate token from a crypto-secure random value
		// 2 hex values per byte
		// ex. 64 hex values for 32 bytes
		$token = bin2hex(openssl_random_pseudo_bytes(32)); 
		
		$_SESSION[$form.'_token'] = $token;
		
		return $token;
	}
	
	function verifyToken($form) {
		// is everything set?
		if (!isset($_SESSION[$form.'_token'])) { 
			echo "<h2>Error: Missing token items</h2><br>This could be a hacking attempt.<br>Please go back to the official form to submit information.<br>";
			return false;
		}
		
		if (!isset($_POST['token'])) {
			echo "<h2>Error: Missing token</h2><br>Go back to the form and resubmit<br>";
			return false;
		}
		
		// does it match?
		if ($_SESSION[$form.'_token'] !== $_POST['token']) {
			echo "<h2>Error: Tokens do not match</h2><br>This could be a hacking attempt.<br>Please go back to the official form to submit information.<br>";
			return false;
		}
		
		// good to go!
		return true;
	}
}

?>