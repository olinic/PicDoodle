// this is accessible from HTML
var allPaths = [];
var allStarts = [];
var strokeIndex = -1;

var passStrength = 0;

var drawing = {doodle: []};

function reportError(error) {
	var output = document.getElementById('error-dialog');
	output.innerText = error;
}

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
	if (strokeIndex > -1) strokeIndex--;

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
	reportError(""); // clear error dialog
	if (checkInputs() && checkEmail() && checkPassword()) {
		submitForm('registerSubmit.php');
	}
}

function login() {
	reportError(""); // clear error dialog
	if (checkInputs() && checkEmail()) {
		submitForm('loginSubmit.php');
	}
}

function submitForm(url) {
	var form = document.getElementById('loginForm');
	form.action = url;
	form.submit();

}


function checkInputs() {
	// make sure that all the inputs have been filled
	// email
	var email = document.getElementById('email');
	var hasEmail = email.value != "";
	if (!hasEmail) {
		// tell user to input email
		reportError("Please enter an email address for the username.");
	}

	// doodle
	var doodle = document.getElementById('doodleInput');
	var hasDoodle = doodle.value.indexOf('"doodle":[]') == -1 && doodle.value != "";
	if (!hasDoodle) {
		// tell user to input doodle
		reportError("Please enter a doodle before continuing.");
	}

	var doodleStrength = /"doodle":\[(\[(\[[0-9]+,[0-9]+\],?)+\],?){3}.*\]/;	// the number 3 requires three strokes
	var doodleLongEnough = doodleStrength.test(doodle.value);
	if (!doodleLongEnough) {
		reportError("Please use at least three strokes for your doodle.")
	}

	// password
	var pass = document.getElementById('password');
	var hasPass = pass.value != "";
	if (!hasPass) {
		// tell user to input password
		reportError("Please enter a password before continuing.");
	}

	return hasEmail && hasDoodle && hasPass && doodleLongEnough;
}

function checkPassword() {
	// (Registration) make sure that the password is strong enough
	var meter = document.getElementById('password-strength-meter');
	var isStrong;

	if (meter.value > 2) isStrong = true;
	else {
		// tell user to use a stronger password
		reportError("Please enter a stronger password before continuing. Required strength: Good.");
		isStrong = false;
	}

	// make sure that the "confirm password" matches
	var matches = true;

	var pass = document.getElementById('password');
	var confirm = document.getElementById('confirm-pass');

	matches = (pass.value == confirm.value);

	if (!matches) reportError("Passwords do not match");

	return isStrong && matches;
}

function checkEmail() {
	var email = document.getElementById('email');
	// check for valid email

	var emailRegex = /.+@.+\..+/;
	var success = emailRegex.test(email.value);

	if (!success) reportError("Please enter a valid email address for the username.");

	return success;
}
