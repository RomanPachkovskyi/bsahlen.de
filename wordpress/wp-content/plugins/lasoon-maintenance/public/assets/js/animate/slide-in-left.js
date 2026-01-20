// Wrap every letter in a span
var textWrapper = document.querySelector('.main-title.slide_in_left');
textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");

anime.timeline({loop: true})
  .add({
    targets: '.main-title.slide_in_left .letter',
    rotateY: [-90, 0],
    duration: 1300,
    delay: (el, i) => 45 * i
  }).add({
    targets: '.main-title.slide_in_left',
    opacity: 0,
    duration: 1000,
    easing: "easeOutExpo",
    delay: 1000
  });