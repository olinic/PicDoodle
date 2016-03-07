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
			echo "missing token items";
			return false;
		}
		
		if (!isset($_POST['token'])) {
			echo "missing token";
			return false;
		}
		
		// does it match?
		if ($_SESSION[$form.'_token'] !== $_POST['token']) {
			echo "tokens do not match";
			return false;
		}
		
		// good to go!
		return true;
	}
}

?>