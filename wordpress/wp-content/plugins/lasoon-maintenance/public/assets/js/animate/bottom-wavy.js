// Wrap every letter in a span
var textWrapper = document.querySelector('.main-title.bottom_wavy_text');
textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");

anime.timeline({loop: true})
  .add({
    targets: '.main-title.bottom_wavy_text .letter',
    translateY: ["1.0em", 0],
    translateZ: 0,
    duration: 750,
    delay: (el, i) => 50 * i
  }).add({
    targets: '.main-title.bottom_wavy_text',
    opacity: 0,
    duration: 1000,
    easing: "easeOutExpo",
    delay: 1000
  });