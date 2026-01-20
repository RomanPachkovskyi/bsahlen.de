/*
	Lovingly stolen (and improved upon, I guess) from Hakim El Hattab
	http://lab.hakim.se/trail/03/js/trail.js
*/

var SCREEN_WIDTH = window.innerWidth;
var SCREEN_HEIGHT = window.innerHeight;
var STARTED = false;
var INTERVAL;

var RADIUS = 110;

var RADIUS_SCALE = 1;
var RADIUS_SCALE_MIN = 1;
var RADIUS_SCALE_MAX = 1.5;

// The number of particles that are used to generate the trail
var QUANTITY = 25;

var canvas;
var context;
var particles;

var mouseX = (SCREEN_WIDTH * 0.5);
var mouseY = (SCREEN_HEIGHT * 0.5);
var mouseIsDown = false;

init();

function init() {

	canvas = document.getElementById( 'tails' );

	if (canvas && canvas.getContext) {
		context = canvas.getContext('2d');

		// Register event listeners
		document.addEventListener('mousemove', documentMouseMoveHandler, false);
		document.addEventListener('mousedown', documentMouseDownHandler, false);
		document.addEventListener('mouseup', documentMouseUpHandler, false);
		canvas.addEventListener('touchstart', canvasTouchStartHandler, false);
		canvas.addEventListener('touchmove', canvasTouchMoveHandler, false);
		window.addEventListener('resize', windowResizeHandler, false);

		createParticles();

		windowResizeHandler();
		
		if(!!SCREEN_HEIGHT){
			STARTED = true;
			INTERVAL = setInterval( loop, 1000 / 64 );
		}
	}
}

function createParticles() {
	particles = [];

	for (var i = 0; i < QUANTITY; i++) {
		var particle = {
			position: { x: mouseX, y: mouseY },
			shift: { x: mouseX, y: mouseY },
			size: 1,
			angle: 0,
			speed: 0.01+Math.random()*0.04,
			targetSize: 1,
			fillColor: '#' + (Math.random() * 0x404040 + 0xaaaaaa | 0).toString(16),
			orbit: RADIUS*.5 + (RADIUS * .5 * Math.random())
		};

		particles.push( particle );
	}
}

function documentMouseMoveHandler(event) {
	mouseX = event.clientX;
	mouseY = event.clientY;
}

function documentMouseDownHandler(event) {
	mouseIsDown = true;
}

function documentMouseUpHandler(event) {
	mouseIsDown = false;
}

function canvasTouchStartHandler(event) {
	if(event.touches.length == 1) {
		event.preventDefault();

		mouseX = event.touches[0].pageX;
		mouseY = event.touches[0].pageY;
	}
}

function canvasTouchMoveHandler(event) {
	if(event.touches.length == 1) {
		event.preventDefault();

		mouseX = event.touches[0].pageX;
		mouseY = event.touches[0].pageY;
	}
}

function windowResizeHandler() {
	SCREEN_WIDTH = window.innerWidth;
	SCREEN_HEIGHT = window.innerHeight;
	canvas.width = SCREEN_WIDTH;
	canvas.height = SCREEN_HEIGHT;
	
	if(!!SCREEN_HEIGHT && !STARTED){
		STARTED = true;
		INTERVAL = setInterval( loop, 1000 / 64 );
	} else if(!SCREEN_HEIGHT){
		STARTED = false;
		clearInterval(INTERVAL);
	}
}

function loop() {

	if( mouseIsDown ) {
		// Scale upward to the max scale
		RADIUS_SCALE += ( RADIUS_SCALE_MAX - RADIUS_SCALE ) * (0.02);
	} else {
		// Scale downward to the min scale
		RADIUS_SCALE -= ( RADIUS_SCALE - RADIUS_SCALE_MIN ) * (0.02);
	}

	RADIUS_SCALE = Math.min( RADIUS_SCALE, RADIUS_SCALE_MAX );

	// Fade out the lines slowly by lowering the opacity, getting image data, clearing the canvas then redrawing
	context.globalCompositeOperation = 'destination-out';// globalAlpha doesn't affect getImageData so we need to use a hacky blend to do it
	context.fillStyle = 'rgba(255, 255, 255, 0.1)';// Unfortunately, this does leave us with a permanent very-low-opacity trail
	context.beginPath();
	context.fillRect(0, 0, SCREEN_WIDTH, SCREEN_HEIGHT);
	context.fill();
	// Back to source-over for the rest of the operation
	context.globalCompositeOperation = 'source-over';
	
	//var CanvasImg = context.getImageData(0, 0, SCREEN_WIDTH, SCREEN_HEIGHT);
	//context.clearRect(0, 0, SCREEN_WIDTH, SCREEN_HEIGHT);
	//context.putImageData(CanvasImg, 0, 0);// Creating an image then redrawing with drawImage works better but the performance is godawful thanks to having to wait for image.onload
	
	/*
		Turns out the globalCompositeOperation I used above to set the alpha on the ImageData actually does the job itself,
		So no need for get/putImageData at all
		And no need for the frame hitching every ~1 second in Firefox caused by getImageData.
	*/

	for (i = 0, len = particles.length; i < len; i++) {
		var particle = particles[i];

		var lp = { x: particle.position.x, y: particle.position.y };

		// Offset the angle to keep the spin going
		particle.angle += particle.speed;

		// Follow mouse with some lag
		particle.shift.x += ( mouseX - particle.shift.x) * (particle.speed);
		particle.shift.y += ( mouseY - particle.shift.y) * (particle.speed);

		// Apply position
		particle.position.x = particle.shift.x + Math.cos(i + particle.angle) * (particle.orbit*RADIUS_SCALE);
		particle.position.y = particle.shift.y + Math.sin(i + particle.angle) * (particle.orbit*RADIUS_SCALE);

		// Limit to screen bounds
		particle.position.x = Math.max( Math.min( particle.position.x, SCREEN_WIDTH ), 0 );
		particle.position.y = Math.max( Math.min( particle.position.y, SCREEN_HEIGHT ), 0 );

		particle.size += ( particle.targetSize - particle.size ) * 0.05;

		// If we're at the target size, set a new one. Think of it like a regular day at work.
		if( Math.round( particle.size ) == Math.round( particle.targetSize ) ) {
			particle.targetSize = 1 + Math.random() * 7;
		}

		context.beginPath();
		context.fillStyle = particle.fillColor;
		context.strokeStyle = particle.fillColor;
		context.lineWidth = particle.size;
		context.moveTo(lp.x, lp.y);
		context.lineTo(particle.position.x, particle.position.y);
		context.stroke();
		context.arc(particle.position.x, particle.position.y, particle.size/2, 0, Math.PI*2, true);
		context.fill();
	}
}
