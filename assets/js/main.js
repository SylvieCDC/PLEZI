//PARALLAX
  window.addEventListener("scroll", function () {
    var parallaxElements = document.querySelectorAll(".parallax-bg");
    var scrollPosition = window.scrollY;
  
    for (var i = 0; i < parallaxElements.length; i++) {
      var parallaxElement = parallaxElements[i];
      var parallaxSpeed = parallaxElement.getAttribute("data-speed");
  
      // Calculez la valeur de décalage en fonction de la position de défilement et de la vitesse
      var parallaxOffset = -scrollPosition * parallaxSpeed;
  
      // Appliquez la valeur de décalage à l'arrière-plan parallaxe
      parallaxElement.style.transform =
        "translateY(" + parallaxOffset + "px)";
    }
  });
  
  // ANIMATION SCROLL ANCRE
  
  function smoothScroll(targetPosition, duration) {
    var startPosition = window.scrollY;
    var distance = targetPosition - startPosition;
    var startTime = null;
  
    function animation(currentTime) {
      if (startTime === null) {
        startTime = currentTime;
      }
      var timeElapsed = currentTime - startTime;
      var scrollOffset = ease(timeElapsed, startPosition, distance, duration);
      window.scrollTo(0, scrollOffset);
      if (timeElapsed < duration) {
        requestAnimationFrame(animation);
      }
    }
  
    function ease(t, b, c, d) {
      t /= d / 2;
      if (t < 1) return (c / 2) * t * t + b;
      t--;
      return (-c / 2) * (t * (t - 2) - 1) + b;
    }
  
    requestAnimationFrame(animation);
  }
  
  
  // Sélectionnez votre élément de lien de la navbar qui pointe vers la section "contact"
  const contactLink = document.querySelector("#secondary_nav a[href='#contact']");
  
  // Ajoutez un gestionnaire d'événement au clic sur le lien
  contactLink.addEventListener("click", function(event) {
    event.preventDefault(); // Empêche le comportement de lien par défaut
  
    // Obtenez la cible du défilement (dans ce cas, l'élément avec l'ID "contact")
    const target = document.querySelector(this.getAttribute("href"));
  
    // Vérifiez si la cible existe avant de faire défiler
    if (target) {
      const offset = target.offsetTop; // Position de défilement cible
      const duration = 2000; // Durée de l'animation en millisecondes
  
      smoothScroll(offset, duration); // Appel de la fonction de défilement animé
    }
  });


 