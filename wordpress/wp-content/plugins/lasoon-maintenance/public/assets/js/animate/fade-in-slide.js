// Wrap every letter in a span
var textWrapper = document.querySelector('.main-title.fade_in_slide');
textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");

anime.timeline({loop: true})
  .add({
    targets: '.main-title.fade_in_slide .letter',
    translateY: [-100,0],
    easing: "easeOutExpo",
    duration: 1400,
    delay: (el, i) => 30 * i
  }).add({
    targets: '.main-title.fade_in_slide',
    opacity: 0,
    duration: 1000,
    easing: "easeOutExpo",
    delay: 1000
  });