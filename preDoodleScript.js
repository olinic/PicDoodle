// this is accessible from HTML
var allPaths = [];
var allStarts = [];
var strokeIndex = -1;

var drawing = {doodle: []};

function updateDoodleInput() {
	var json = JSON.stringify(drawing);
	var input = document.getElementById('doodleInput');
	input.value = json;
}

function resetDoodle() {
	// reset the index
	strokeIndex = -1;

	while (allPaths.length > 0) {
		var path = allPaths.pop();
		console.log("popped");
		
		// each path is guaranteed to have a start but not guaranteed to have a line
		while (path.length > 0) {
			var element = path.pop();
			element.remove();
		}
	}

	// reset drawing
	drawing = {doodle: []};
	updateDoodleInput();
}

function undoStroke() {
	strokeIndex--;
	
	var lastStroke = allPaths.pop();
	
	while (lastStroke.length > 0) {
		var element = lastStroke.pop();
		element.remove();
	}
	
	drawing.doodle.pop(); // remove last stroke
	updateDoodleInput();
}

function addPath(path) {
	allPaths[allPaths.length - 1][1] = path; // add the path to the last element
}

function addStart(start) {
	allPaths.push([start]); // insert a new start
}

function register() {
	var goodToGo = checkInputs();
	

	var input = document.getElementById('doodleInput');
	
	console.log(input.value);
	
	if (goodToGo) {
		submitForm('register.php');
	}
}

function login() {
	submitForm('login.php');
}

function submitForm(url) {
	var form = document.getElementById('loginForm');
	form.action = url;
	form.submit();
	
}

var passStrength = 0;

function checkInputs() {
	var meter = document.getElementById('password-strength-meter');
	var email;
	// check for valid email
	
	
	
	if (meter.value > 2) return true;
	else {
		// tell user to use a stronger password
		
		return false;
	}
}
