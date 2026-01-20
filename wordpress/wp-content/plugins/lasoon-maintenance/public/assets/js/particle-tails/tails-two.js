/*
 * Copyright MIT © <2013> <Francesco Trillini>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated 
 * documentation files (the "Software"), to deal in the Software without restriction, including without limitation 
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and 
 * to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, 
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR 
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE 
 * FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, 
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

var Orbit = {}; 
 
;(function(Orbit, undefined) {
	
	var self = window.Orbit || {}, canvas, context, mouse = { x: innerWidth / 2, y: innerHeight / 2 }, particles = [], FPS = 60;
	
	// Dat GUI default values
	var ease = 0.05, size = 10, speed = 0.02, orbit = 100, trail = 0.05, interactive = true;
	
	/*
	 * Settings.
	 */
	
	var Settings = function() {
		
		this.ease = 0.05;
		this.size = 10;
		this.speed = 0.02;
		this.orbit = 100;
		this.trail = 0.05;
		this.interactive = true;
		
		this.changeEase = function(value) {
		
			ease = value;
		
		};
		
		this.changeSize = function(value) {
		
			size = value;
		
		};
		
		this.changeSpeed = function(value) {
		
			speed = value;
		
		};
		
		this.changeOrbit = function(value) {
					
			orbit = value;
		
		};
		
		this.changeTrail = function(value) {
		
			trail = value;
		
		};
		
		this.enableInteractivity = function(value) {
		
			interactive = value;
			
			mouse = { 
			
				x: innerWidth * 0.5, 
				y: innerHeight * 0.5 
				
			};
		
		};
		
		this.deleteAll = function(value) {
		
			particles = [];
		
		};
				
	};	
		
	/*
 	 * Init.
	 */
	
	self.init = function() {
		
		var settings = new Settings();
		var GUI = new dat.GUI();
		
		// Dat GUI main
		GUI.add(settings, 'ease').min(0.01).max(0.09).onChange(settings.changeEase);
		GUI.add(settings, 'size').min(1).max(30).onChange(settings.changeSize);
		GUI.add(settings, 'speed').min(0.01).max(0.08).onChange(settings.changeSpeed);
		GUI.add(settings, 'orbit').min(100).max(300).onChange(settings.changeOrbit);
		GUI.add(settings, 'trail').min(0.01).max(0.09).onChange(settings.changeTrail);
		GUI.add(settings, 'interactive').onChange(settings.enableInteractivity);
		GUI.add(settings, 'deleteAll').onChange(settings.deleteAll);
		
		var body = document.querySelector('body');
		
		canvas = document.createElement('canvas');
			
		canvas.width = innerWidth;
		canvas.height = innerHeight;
		
		canvas.style.backgroundColor = 'rgba(0,0,0, 0.6)!important';
		canvas.style.position = 'absolute';
		canvas.style.left = 0;
		canvas.style.bottom = 0;
		canvas.style.left = 0;
		canvas.style.right = 0;
		canvas.style.zIndex = -1;
				
        body.appendChild(canvas);
		
		// Browser supports canvas?
		if(!!(self.gotSupport())) {
		
			context = canvas.getContext('2d');
		
			// Events
			if('ontouchstart' in window) {
			
				canvas.addEventListener('touchstart', self.onTouchStart, false);
				document.addEventListener('touchmove', self.onTouchMove, false);
				
			}
				
			else {
			
				canvas.addEventListener('mousedown', self.onMouseDown, false);
				document.addEventListener('mousemove', self.onMouseMove, false);
				
			}
			
			window.onresize = onResize;
			
			// Let's create our particles
			self.createParticles();
			
		}
		
		else {
		
			console.error("Sorry, your browser doesn't support canvas.");
		
		}
        
	};
	
	/*
	 * On resize window event.
	 */
	
	function onResize() {
	
		canvas.width = window.innerWidth;
		canvas.height = window.innerHeight;
	
	}
	
	/*
	 * Check if browser supports canvas element.
	 */
	
	self.gotSupport = function() {
	
		return canvas.getContext && canvas.getContext('2d');
	
	};
	
	/*
	 * Mouse down event.
	 */
	
	self.onMouseDown = function(event) {
	
		event.preventDefault();
	
		self.addParticles();
			
	};
		
	/*
	 * Mouse move event.
	 */
	
	self.onMouseMove = function(event) {
	
		event.preventDefault();
	
		if(interactive) {
	
			mouse.x = event.pageX - canvas.offsetLeft;
			mouse.y = event.pageY - canvas.offsetTop;
			
		}
			
	};
		
	/*
	 * Touch start event.
	 */
	
	self.onTouchStart = function(event) {
	
		event.preventDefault();
		
		self.addParticles();
			
	};	
		
	/*
	 * Touch move event.
	 */
	
	self.onTouchMove = function(event) {
	
		event.preventDefault();
		
		if(interactive) {
		
			mouse.x = event.touches[0].pageX - canvas.offsetLeft;
			mouse.y = event.touches[0].pageY - canvas.offsetTop;
			
		}
			
	};
	
	/*
	 * Create the particles.
	 */
	
	self.createParticles = function() {
				
		for(var quantity = 0, len = 50; quantity < len; quantity++)
		
			particles.push({
			
				x: mouse.x,
				y: mouse.y,
				lastX: mouse.x,
				lastY: mouse.y,
				originX: mouse.x,
				originY: mouse.y,
				angle: 0,
				radius: size,
				speed: (speed * 0.5) + Math.random() * speed,
				orbit: self.randomBetween(50, 100),
				minOrbit: self.randomBetween(50, 100),
				offset: - Math.random() * 3
				
			});
			
		self.animate();
	
	};
	
	/*
	 * Add new particles.
	 */
	
	self.addParticles = function() {
				
		var quantity = 10;
			
		while(quantity--) {
			
			var x = interactive ? mouse.x : Math.random() * canvas.width;
			var y = interactive ? mouse.y : Math.random() * canvas.height;
			
			particles.push({
			
				x: x,
				y: y,
				lastX: x,
				lastY: y,
				originX: x,
				originY: y,
				angle: 0,
				radius: size,
				speed: (speed * 0.5) + Math.random() * speed,
				orbit: 0,
				minOrbit: self.randomBetween(50, 100),
				offset: 0
				
			});
			
		}
	
	};
	
	/*
	 * Let's animate our orbit.
	 */
	
	self.animate = function() {
		
		// Logic
		self.clear();
		self.update();
		self.render();
		
		requestAnimFrame(self.animate);
	
	};
	
	/*
	 * Clear the whole screen, with a little effect of trail.
	 */
	
	self.clear = function() {
	
		context.fillStyle = 'rgba(255, 255, 255, ' + trail + ')';
		context.fillRect(0, 0, canvas.width, canvas.height);
	
	};
	
	/*
	 * Update the animation.
	 */
	
	self.update = function() {
		
		[].forEach.call(particles, function(particle, index) {
			
			// Last position
			particle.lastX = particle.x;
			particle.lastY = particle.y;
			
			// Circular movement with soft ease
			particle.originX += (mouse.x - particle.originX) * (particle.speed + ease);
			particle.originY += (mouse.y - particle.originY) * (particle.speed + ease);
			
			// Towards goal destination with soft ease
			particle.radius += (size - particle.radius) * (particle.speed + ease);
			particle.speed += (speed - particle.speed) * (particle.speed + ease);
			particle.orbit += (particle.minOrbit - particle.orbit) * (particle.speed + ease);
			particle.offset += (orbit / 100 - particle.offset) * (particle.speed + ease);
		
			// Rotation			
			particle.x = particle.originX + Math.sin(index + particle.angle) * particle.orbit * particle.offset;
			particle.y = particle.originY + Math.cos(index + particle.angle) * particle.orbit * particle.offset;
			
			particle.angle += particle.speed;
			
			// Screen bounds
			particle.x = Math.max(particle.radius * 0.5, Math.min(particle.x, innerWidth - particle.radius * 0.5));
			particle.y = Math.max(particle.radius * 0.5, Math.min(particle.y, innerHeight - particle.radius * 0.5));
					
		});
	
	};
	
	/*
	 * Render the animation.
	 */
	
	self.render = function() {
		
		[].forEach.call(particles, function(particle, index) {
							
			context.save();
			context.globalAlpha = 1.0;
			context.strokeStyle = 'hsl' + '(' + ((particle.x / canvas.width + particle.y / canvas.height) * 180) + ', 100%, 70%)'; 
			context.lineWidth = particle.radius;	
			context.lineCap = 'round';
			context.lineJoin = 'round';
			context.beginPath();
			context.moveTo(particle.lastX, particle.lastY);
			context.lineTo(particle.x, particle.y);
			context.closePath();
			context.stroke();
			context.fillStyle = 'hsl' + '(' + ((particle.x / canvas.width + particle.y / canvas.height) * 180) + ', 100%, 70%)';  
			context.beginPath();
			context.arc(particle.x, particle.y, particle.radius / 2, 0, Math.PI * 2);
			context.fill();
			context.closePath();
			context.restore();
			
		});
	
	};
	
	/*
	 * Useful function for random integer between [min, max].
	 */
	
	self.randomBetween = function(min, max) {
	
		return ~~(Math.random() * (max - min + 1) + min);

	};
	
	/*
	 * Request new frame by Paul Irish.
	 * 60 FPS.
	 */
	
	window.requestAnimFrame = (function() {
	 
		return  window.requestAnimationFrame       || 
				window.webkitRequestAnimationFrame || 
				window.mozRequestAnimationFrame    || 
				window.oRequestAnimationFrame      || 
				window.msRequestAnimationFrame     || 
			  
				function(callback) {
			  
					window.setTimeout(callback, 1000 / FPS);
				
				};
			  
    	})();

	window.addEventListener ? window.addEventListener('load', self.init, false) : window.onload = self.init;
	
})(Orbit);