/* ================================================
             Background Image Slider
   ================================================ */

jQuery(document).ready(function($){ 
  "use strict";
  function cycleBackgrounds() {
    var index = 0;

    var imageEls = jQuery('.Slider .Slider-slide'); // Get the images to be cycled.
    if(imageEls.length != 1){
      setInterval(function () {
        // Get the next index.  If at end, restart to the beginning.

        index = index + 1 < imageEls.length ? index + 1 : 0;        
        // Show the next
        imageEls.eq(index).addClass('show');
        // Hide the previous
        imageEls.eq(index - 1).removeClass('show');
      }, 3000);
    }
  };


  // Document Ready.
  jQuery(function () {
    cycleBackgrounds();
  });
  
  /* ================================================
             Sidebar Js
   ================================================ */

  jQuery('.sidebar-btn').click(function(){
    jQuery('.main_side').toggleClass("show");
    jQuery('.close-btn').toggleClass("click");

  });


  // To remove Sidebar click on body area

  $(document).click(function() {
    var container = $(".main_side");
    var sideBtn = $(".sidebar-btn");
    var closeBtn = $(".close-btn");

    if (!sideBtn.is(event.target) && !sideBtn.has(event.target).length && !container.is(event.target) && !container.has(event.target).length) {
      container.removeClass('show');
      closeBtn.removeClass('click');
    }
  });

  /* ================================================
               Contact Form Pop-up
     ================================================ */

    jQuery(document).on('click', '.toggle-btn', function(event) {
      event.preventDefault();
      var target = jQuery(this).data('target');
      jQuery('#' + target).toggleClass('hide');
    });

    var pageRain = $('.rainy');
    if (pageRain.length && !isMobile && !isTouch) {
      var image = pageRain[0];
      image.onload = function () {
        var engine = new RainyDay({
          image: this,
          parentElement: $('.section-overlay')[0]
        });
            engine.rain([[1, 2, 4000]]); 
            engine.rain(
              [
              [3, 3, 1], [5, 5, 1], [6, 2, 1]
              ],
              100); 
          };
        }

    /* ================================================
             Ripple Js
    ================================================ */

      if($('body').hasClass('ripple')){

        $('body.ripple .page-wrap').ripples({
          resolution: 400,
          dropRadius: 30,
          perturbance: 0.09,
        });
      }

      if($('section').hasClass('ripple')){

        $('section.ripple').ripples({
          resolution: 400,
          dropRadius: 30,
          perturbance: 0.09,
        });
      }

      document.addEventListener("DOMContentLoaded", function(){
          /////// Prevent closing from click inside dropdown
          document.querySelectorAll('.dropdown-menu').forEach(function(element){
            element.addEventListener('click', function (e) {
              e.stopPropagation();
            });
          })
        }); 

      /* ================================================
          Particles Js
      ================================================ */

              if (jQuery(window).width() > 767) {
                if($('body').hasClass('particles')){
                  $("body.particles").jParticle({
                    color: "#fff",
                    linkDist: 100,
                    particlesNumber: 90,
                    particle: {
                      color: "#fff",
                      minSize: 6,
                      maxSize: 8,
                      speed: 50
                    }
                  });
                }
              } else {
                if($('body').hasClass('particles')){

                  $("body.particles").jParticle({
                    color: "#fff",
                    linkDist: 45,
                    particlesNumber: 20,
                    particle: {
                      color: "#fff",
                      minSize: 3,
                      maxSize: 6,
                      speed: 30
                    }
                  });
                }
              }

      /* ================================================
                 Section Particles Js
      ================================================ */

              if (jQuery(window).width() > 991) {
                if($('section').hasClass('particles')){
                  $("section.particles").jParticle({
                    color: "#fff",
                    linkDist: 80,
                    particlesNumber: 60,
                    particle: {
                      color: "#fff",
                      minSize: 5,
                      maxSize: 7,
                      speed: 30
                    }
                  });
                }
              } else {
                if($('section').hasClass('particles')){

                  $("section.particles").jParticle({
                    color: "#fff",
                    linkDist: 80,
                    particlesNumber: 40,
                    particle: {
                      color: "#fff",
                      minSize: 5,
                      maxSize: 7,
                      speed: 30
                    }
                  });
                }
              }

      /* ================================================
                 Lighting Ball Js
      ================================================ */

              if($('body').hasClass('lighting_ball')){

                new Particles(document.getElementById("particles"), {

              // size of the particles
              size: { 
                min: 0,
                max: 2
              },

              // density of particles on the canvas
              density: 600,  

              // speed of the particules
              speed: 0.50, 

              // number of times per second the canvas is refreshed
              fps: 60, 

              // color of the particles
              color: "#ffffff" 

            });
              }

      /* ================================================
              Section Lighting Ball Js
      ================================================ */

              if($('section').hasClass('lighting_ball')){

                new Particles(document.getElementById("particles"), {

              // size of the particles
              size: { 
                min: 0,
                max: 2
              },

              // density of particles on the canvas
              density: 600,  

              // speed of the particules
              speed: 0.50, 

              // number of times per second the canvas is refreshed
              fps: 60, 

              // color of the particles
              color: "#ffffff" 

            });
              }

      /* ================================================
              Iframe Video
      ================================================*/

              var currentSrc = jQuery('#player iframe').attr('src');
              if(currentSrc && currentSrc.match("www.youtube.com")){
                if(currentSrc.includes("?")){
                  var newSrc = currentSrc + '&autoplay=1&controls=0&loop=1&mute=1';
                } else {
                  var newSrc = currentSrc + '?&autoplay=1&controls=0&loop=1&mute=1';
                }

              }
              else if(currentSrc && currentSrc.match("player.vimeo.com")) {
                var newSrc = currentSrc + '&autoplay=1&loop=1&;autopause=0&;muted=1';

              }
              jQuery('#player iframe').attr('src', newSrc);

            });







