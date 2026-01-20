//Corner Bottom Text Effect

var textWrapper = document.querySelector('.main-title.corner_down');
textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");

anime.timeline({loop: true})
  .add({
    targets: '.main-title.corner_down .letter',
    translateY: ["1.1em", 0],
    translateX: ["0.55em", 0],
    translateZ: 0,
    rotateZ: [180, 0],
    duration: 750,
    easing: "easeOutExpo",
    delay: (el, i) => 50 * i
  }).add({
    targets: '.main-title.corner_down',
    opacity: 0,
    duration: 1000,
    easing: "easeOutExpo",
    delay: 1000
  });