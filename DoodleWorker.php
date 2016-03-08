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
	
	function __construct($thres = 5) {
		
		$this->max = $thres;
	}
	
	function verifyDoodle($given, $original) {
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
				
				if (!$this->diffTests($diff)) $success = false; // if tests fail, the update success
			}
			else {
				// make up work to avoid timing attack
				
			}
		}
		
		
		// authentication is successful when all strokes are authenticated
		return $success;
	}
	
	function diffTests($diff) {
		// input: difference array between two strokes
		// performs various tests to determine if the strokes are "close" enough
		
		
		return true;
	}
	
	function test1($diff) {
		// MAX test
		
	}
	
	function test2($diff) {
		
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
		
		$results = [];
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
		
		// can calculate distance for each point or calculate it
		
		// calculate
		$xDiff = $p1[0] - $p2[0];
		$yDiff = $p1[1] - $p2[1];
		return sqrt(($xDiff)**2 + ($yDiff)**2);
	}
	
}

?>