document.addEventListener('DOMContentLoaded', function() {
  const SelectElement = function (element) {
    return document.querySelector(element);
  };

  let menuToggler = SelectElement('.cta_menu a');
  let menu = SelectElement('.mobile_menu');

  menuToggler.addEventListener('click', function (event) {
    event.preventDefault();
    menu.classList.toggle('open');
  });

  window.sr = ScrollReveal({
    viewFactor: 0.2, // La section doit être à 20% visible pour déclencher l'animation
    reset: true // Réinitialise l'animation lorsqu'elle n'est plus visible
  });

  sr.reveal('.parallax-section .restaurant-descripton.animate-left', {
    origin: 'left',
    duration: 2000,
    distance: '25rem',
    delay: 600,
  });

  sr.reveal('.parallax-section .restaurant-info-img.animate-right', {
    origin: 'right',
    duration: 2000,
    distance: '25rem',
    delay: 300,
  });

  sr.reveal('.animate-top', {
    origin: 'top',
    duration: 2000,
    distance: '25rem',
    delay: 300,
  });

  sr.reveal('.animate-bottom', {
    origin: 'bottom',
    duration: 2000,
    distance: '25rem',
    delay: 300,
  });
  
  sr.reveal('.parallax-section .animate-left', {
    origin: 'left',
    duration: 2000,
    distance: '25rem',
    delay: 300,
  });
  
  sr.reveal('.parallax-section .animate-right', {
    origin: 'right',
    duration: 2000,
    distance: '25rem',
    delay: 300,
  });
  
  sr.reveal('.parallax-section .animate-top', {
    origin: 'top',
    duration: 2000,
    distance: '25rem',
    delay: 300,
  });
  
  sr.reveal('.parallax-section .animate-bottom', {
    origin: 'bottom',
    duration: 2000,
    distance: '25rem',
    delay: 300,
  });
});

/* texte animé slogan*/
console.clear();

    var textPath = document.querySelector('#text-path');
    var textContainer = document.querySelector('#text-container');
    var path = document.querySelector(textPath.getAttribute('href'));
    var pathLength = path.getTotalLength();
    console.log(pathLength);

    function updateTextPathOffset(offset) {
        textPath.setAttribute('startOffset', offset);
    }

    updateTextPathOffset(pathLength);

    function onScroll() {
        requestAnimationFrame(function () {
            var rect = textContainer.getBoundingClientRect();
            var scrollPercent = rect.y / window.innerHeight;
            console.log(scrollPercent);
            updateTextPathOffset(scrollPercent * 0.5 * pathLength);
        });
    }

    window.addEventListener('scroll', onScroll);
