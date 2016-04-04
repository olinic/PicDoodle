// this can access everything in preDoodleScript.js

var image = document.getElementById('passPic');
var imgWidth = image.clientWidth;
var imgHeight = image.clientHeight
var wider = imgWidth > imgHeight;
var unitLength;



var divisor = 200;

if (wider) unitLength = imgWidth / divisor;
else unitLength = imgHeight / divisor;

console.log(unitLength);

// The minimum distance the mouse has to drag
// before firing the next onMouseDrag event:
var multiplier = 2.5; // defines the length of each segment (number of units)
tool.minDistance = unitLength*multiplier;
tool.maxDistance = unitLength*multiplier;


//showLines();

resetDoodle();

function showLines() { // grid lines for units
	for (var a=0; a < imgWidth/unitLength; a++) {
		var from = new Point(a*unitLength, 0);
		var to = new Point(a*unitLength, imgHeight);
		var line = new Path.Line(from, to);
		line.strokeColor = 'white';
	}

	for (var b=0; b < imgHeight/unitLength; b++) {
		var from = new Point(0, b*unitLength);
		var to = new Point(imgWidth, b*unitLength);
		var line = new Path.Line(from, to);
		line.strokeColor = 'white';
	}
}

function getX(event) {
	return Math.round(event.point.x / unitLength);
}

function getY(event) {
	return Math.round(event.point.y / unitLength);
}

function addStroke() {
	drawing.doodle.push([]);
	strokeIndex += 1;
}

function addPoint(x, y) {
	drawing.doodle[strokeIndex].push([x,y]);
}

function onMouseDown(event) {
	// Create a new path and give it a stroke color:

	path = new Path();
	path.fillColor = {
		hue: Math.random() * 360,
		saturation: 1,
		brightness: 1
	};

	// Add a segment to the path where
	// you clicked:
	path.add(event.point);
	console.log(event.point);

	var start = new Path.Circle({
		center: event.point,
		radius: 5,
		fillColor: path.fillColor
	});

	addStart(start); // add circle so that we can remove it if need be

	addStroke();
	addPoint(getX(event), getY(event));
}

function onMouseDrag(event) {
	var x = getX(event);
	var y = getY(event);
	//console.log("x: " + x + ", y: " + y);

	var step = event.delta / multiplier;
	step.angle += 90;

	var top = event.middlePoint + step;
	var bottom = event.middlePoint - step;

	path.add(top);
	path.insert(0, bottom);
	//path.smooth();

	addPoint(x, y);
}

function onMouseUp(event) {
	path.add(event.point);
	path.closed = true;
	addPath(path);
	//path.smooth();

	//addPoint(getX(event), getY(event)); // point was already recorded on drag event
	console.log(drawing);

	// put doodle in input as JSON
	updateDoodleInput();
}



//onMouseDown="startRecordingMouse(event);" onMouseUp="abortRecording(event);" onMouseMove="reportMouseLocation(event);"
