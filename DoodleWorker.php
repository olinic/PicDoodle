<?php
class Node {
	public $cost;
	public $prev;
	public $total;
	
	function __construct() {
		$this->cost = 0;		// individual cost
		$this->prev = null;		// previous node
		$this->total = 1048575;	// total cost
	}
}

class DoodleWorker {
	protected $max;
	protected $avg;
	
	
	/*
	Items that are saved from previous comparison:
	
	Errors - an array of errors that caused failure of authentication
	Percent match (for users only) - not used for pass / fail
		Exact = 100%
		Far away = 0%
		
		Get percent match for each stroke, then average them.
		Get the percent match for each point, then average them for the stroke.
		
	*/
	protected $errors;
	protected $errorCodes;
	
	// used to calculate percent match
	protected $totalPercent;
	protected $numPercents;
	
	
	function __construct($maxThres = 12, $avgThres = 7) {
		
		$this->max = $maxThres;
		$this->avg = $avgThres;
		
		$this->totalPercent = 0;
		$this->numPercents = 0;
		
		$this->errors = [];
		
		$this->errorCodes = [
			0 => "Unknown",
			1 => "Incorrect number of strokes",
			2 => "Exceeded max distance",
			3 => "Exceeded average distance",
		];
	}
	
	function verifyDoodle($given, $original) {
		$this->clearDetails();
		$success = true;
		
		// parse JSON
		$gJson = json_decode($given);
		$oJson = json_decode($original);
		
		// get all properties
		$gProp = get_object_vars($gJson);
		$oProp = get_object_vars($oJson);
		
		// if number of properties do not match, then fail
		if (count($gProp) != count($oProp)) $success = false;
		
		// compare extra items
		
		
		// compare doodles
		$gDoodle = $gJson->{'doodle'};
		$oDoodle = $oJson->{'doodle'};
		
		// each doodle can come with multiple strokes
		// if stroke count doesn't match, then the doodles do not match
		if (count($gDoodle) != count($oDoodle)) $success = false;
		
		// if false, output to errors
		if (!$success) $this->saveError(1);
		
		// we could reject if the number of strokes don't match, 
		// but this could lead to a timing attack 
		// (attacker is quickly rejected when the number of strokes don't match 
		//  	so the attacker can learn how many strokes the original doodle is)
		// to avoid timing attack, we will do as much work as the given (do not reveal information about the original)
		for ($i=0; $i < count($gDoodle); $i++) {
			if ($i < count($oDoodle)) {
				// compare doodles normally
				// verify each stroke
				$diff = $this->getDifferences($gDoodle[$i], $oDoodle[$i]);
				
				// calc percentage 
				for ($a=0; $a < count($diff); $a++) {
					$this->addPercent($diff[$a]);
				}
				
				print_r($diff);
				echo "<br>";
				
				if (!$this->diffTests($diff)) $success = false; // if tests fail, the update success
			}
			else {
				// an extra stroke was made
				// create work to avoid timing attack
				
			}
		}
		
		
		// authentication is successful when all strokes are authenticated
		return $success;
	}
	
	function diffTests($diff) {
		// input: difference array between two strokes
		// performs various tests to determine if the strokes are "close" enough
		
		$maxTest = $this->test2($diff);
		$avgTest = $this->test1($diff);
		
		$success = $maxTest && $avgTest;
		
		return $success;
	}
	
	function test1($diff) {
		// MAX test (less strict)
		$thres = $this->max;
		
		$success = true;
		for ($i=0; $i < count($diff); $i++) {
			if ($diff[$i] > $thres) $success = false;
		}
		
		// if false, output to errors
		if (!$success) $this->saveError(2);
		
		return $success;
	}
	
	function test2($diff) {
		// AVERAGE test (more strict)
		$thres = $this->avg;
		
		$total = 0;
		for ($i=0; $i < count($diff); $i++) {
			$total += $diff[$i];
		}
		
		$average = $total / count($diff);
		
		echo "<b>Average</b> is $average.<br>";
		$success = $average < $thres;
		
		// if false, output to errors
		if (!$success) $this->saveError(3);
		
		return $success;
	}
	
	function test3($diff) {
		// could be EUCLIDEAN DISTANCE, MEAN SQUARED ERROR, or etc. 
		
	}
	
	function getDifferences($d1, $d2) {
		// this is where most of the work occurs
		// returns an array of differences using DTW
		
		// setting up DTW
		$dtw = [];
		
		for ($i=-1; $i < count($d1); $i++) {
			$dtw[$i] = []; 	// create 2 dimensional array
			for ($a=-1; $a < count($d2); $a++) {
				$dtw[$i][$a] = new Node(); 
			}
		}
		
		$dtw[-1][-1]->total = 0;
		
		// start DTW
		// DTW will always match the first nodes and the last nodes together
		for ($i=0; $i < count($d1); $i++) {
			for ($a=0; $a < count($d2); $a++) {
				$dtw[$i][$a]->cost = $this->calCost($d1[$i], $d2[$a]);
				
				$min;
				$x = $dtw[$i-1][$a]->total;
				$y = $dtw[$i][$a-1]->total;
				$z = $dtw[$i-1][$a-1]->total;
				
				// set min to the smallest
				// set the prev so we can backtrack
				if ($x < $y and $x < $z) {
					$min = $x;
					$dtw[$i][$a]->prev = $dtw[$i-1][$a];
					
				} else if ($y < $x and $y < $z) {
					$min = $y;
					$dtw[$i][$a]->prev = $dtw[$i][$a-1];
					
				} else {
					$min = $z;
					$dtw[$i][$a]->prev = $dtw[$i-1][$a-1];
				}
				
				$dtw[$i][$a]->total = $dtw[$i][$a]->cost + $min;
				
			}
		}
		
		$dtw[0][0]->prev = null; 		// the starting node does not need the previous to be set
		
		$m = count($d1);
		$n = count($d2);
		
		$results = [];					// save all the distances
		$node = $dtw[$m-1][$n-1]; 		// start with the last element
		
		while ($node != null) {			// work backwards
			$results[] = $node->cost; 	// insert the cost into the next index of the array
			$node = $node->prev;
		}
		
		$results = array_reverse($results); 		// reverse the array so that it is back in order
		
		return $results;
	}
	
	function calCost($p1, $p2) {
		// the cost is the euclidean distance
		
		// calculate
		$xDiff = $p1[0] - $p2[0];
		$yDiff = $p1[1] - $p2[1];
		return sqrt(($xDiff)**2 + ($yDiff)**2);
	}
	
	function clearDetails() {
		// clears error variable
		$this->errors = [];
		$this->totalPercent = 0;
		$this->numPercents = 0;
	}
	
	function saveError($errorCode) {
		if (array_key_exists($errorCode, $this->errorCodes)) {
			$this->errors[$errorCode] = $this->errorCodes[$errorCode];
		} else {
			$this->errors[0] = $this->errorCodes[0]; // unknown error
		}
	}
	
	function randomStroke($len) {
		// creates a stroke with random points
		
	}
	
	function doodleStrength($d) {
		// returns the strength of the doodle
		
	}
	
	function calcPercentMatch() {
		if ($this->numPercents != 0) $percent = $this->totalPercent / $this->numPercents; // the average of all the points' percentages
		else $percent = 0;
		
		$percent *= 100;
		$percent = round($percent);
		
		return (int) $percent;
	}
	
	function addPercent($distance) {
		/*
		What is the percent match for a point?
		1 - Distance / 60
			Do not allow negative numbers
		*/
		$percent = 1 - $distance/60;
		
		if ($percent < 0) $percent = 0;
		
		$this->totalPercent += $percent;
		$this->numPercents += 1;
	}
	
	function doodleDetails() {
		/* returns the details of the last doodle comparison
		Includes
			Errors - what caused failure
			Percent Match - give the user an idea of how close the doodles were (no major significance)
		
		*/
		$details = [
			"percentMatch" => $this->calcPercentMatch(),
			"errors" => $this->errors,
		];
		
		return $details;
		
	}
	
}

?>