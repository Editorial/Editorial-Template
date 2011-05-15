var bubbles = new Array;
var drawInterval = "";
var createInterval = "";
var canvas;
var context;
var trackMouse = false;

function clearCanvas() {
    context.clearRect(0, 0, canvas.width, canvas.height);
}

function saveCanvas() {
	context.save();
}

function restoreCanvas() {
	context.restore();
}

function Bubble(x, y, size, angle, speed) {
	this.x = x;
	this.y = y;
	this.size = size;
	this.angle = angle;
	this.speed = speed;
}

function drawBubbles() {
	saveCanvas();
	clearCanvas();
	var c=$('#canvas');
	for (i in bubbles) {
		bubble = bubbles[i];
		//$('#canvas').drawImage({
		c.drawImage({
			source: 'images/bubble.png',
			x: bubble.x,
			y: bubble.y,
			width: bubble.size,
			height: bubble.size
		});
		// stop tracking bubble
		if (bubble.x <= bubble.size || bubble.y <= bubble.size || bubble.x > 900 || bubble.y > 450) {
			bubbles.splice(i,1);
			continue;
		}
		// move bubble
		bubble.x += Math.cos(bubble.angle)*bubble.speed;
		bubble.y += Math.sin(bubble.angle)*bubble.speed;
		bubbles[i] = bubble;
	}
	restoreCanvas();
	if (bubbles.length == 0 && drawInterval != "") {
		// remove interval
		clearCanvas();
		clearInterval(drawInterval);
		drawInterval = "";
	}
}

function generateBubbles() {
	var originX = 900, originY = 100;
	if (bubbles.length < 75) {
		// create just one new bubble per generate call
		bubble = new Bubble(originX, originY, Math.floor(Math.random() * 90 ), Math.random()*Math.PI + Math.PI/2, Math.random()*4+1);
		bubbles.push(bubble);
	}
	// get mouse position over the coming soon button
}

$('document').ready(function() {
	canvas = document.getElementById("canvas");
    context = canvas.getContext("2d");	
	// twitter behaviour
	$('#twitter').hover(function() {
		$("#follow").fadeIn();
	}, function() {
		$("#follow").fadeOut();
	});
	
	// canvas
	$('#soon').mousedown(function(e) {
		if (drawInterval == "") {
			drawInterval = setInterval('drawBubbles()', 33);
		}
		if (createInterval == "") {
			createInterval = setInterval('generateBubbles()', 25);
		}
		// track mouse
		trackMouse = true;
		$(this).mousemove(function(e) {
			if (!trackMouse) return;
			var curX = e.pageX - this.offsetLeft;
			var curY = e.pageY - this.offsetTop;
			console.log("{"+this.offsetLeft+","+this.offsetTop+"}, {"+e.pageX+","+e.pageY+"}, {"+curX+","+curY+"}");
		});
	}).mouseup(function() {
		clearInterval(createInterval);
		createInterval = "";
		// stop tracking mouse
		trackMouse = false;
	});
	
	// canvas click hittests for bubble and removes it
	$('#canvas').click(function(e) {
		// loop throught bubbles and hittest
		for(i in bubbles) {
			bubble = bubbles[i];
			r = bubble.size/2;
			if (Math.abs(bubble.x - e.offsetX) <= r && Math.abs(bubble.y - e.offsetY) <= r) {
				bubbles.splice(i,1);
				// remove only one
				return;
			}
		}
	});
	
	
	// tweet
	$('#tweet').tweet({
		username: 'editorialtheme',
		loading_text: 'loading tweet ...',
		count: 1
	});
});