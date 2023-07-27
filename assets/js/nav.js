document.addEventListener("DOMContentLoaded", function () {
  var secondaryNavLinks = document
    .querySelector(".mobile_menu")
    .getElementsByTagName("a");
  var menu = document.querySelector(".mobile_menu");

  for (var i = 0; i < secondaryNavLinks.length; i++) {
    secondaryNavLinks[i].addEventListener("click", function (event) {
      // Fermer le menu mobile
      menu.classList.remove("visible");
      menu.classList.add("hidden");

      // Animation scroll smooth vers le point d'ancrage
      var targetId = this.getAttribute("href");
      var targetElement = document.querySelector(targetId);
      if (targetElement) {
        event.preventDefault(); // Empêcher le comportement par défaut du lien

        var offsetTop = targetElement.offsetTop;
        var duration = 1000; // Durée de l'animation en millisecondes (par exemple, 1000 pour 1 seconde)

        scrollToElement(targetElement, offsetTop, duration);
      }
    });
  }

  navbar.addEventListener("mouseleave", function () {
    // Supprimer la classe active de tous les liens lorsque la souris quitte la barre de navigation
    for (var i = 0; i < secondaryNavLinks.length; i++) {
      secondaryNavLinks[i].classList.remove("active");
    }
  });

  // Menu toggle
  const btn = document.querySelector(".mobile_menu_button");

  btn.addEventListener("click", () => {
    menu.classList.toggle("hidden");
    menu.classList.toggle("visible");
  });

  // Fermer le menu mobile si la largeur de la page est supérieure à 805px
  function closeMenuIfLargeScreen() {
    const screenWidth = window.innerWidth;

    if (screenWidth > 805 && !menu.classList.contains("hidden")) {
      menu.classList.remove("visible");
      menu.classList.add("hidden");
    }
  }

  // Gérer le redimensionnement de l'écran
  window.addEventListener("resize", closeMenuIfLargeScreen);

  // Appeler la fonction de fermeture du menu au chargement de la page
  closeMenuIfLargeScreen();

  // Fonction pour effectuer l'animation de défilement
  function scrollToElement(element, offsetTop, duration) {
    var start = window.pageYOffset;
    var startTime =
      "now" in window.performance
        ? performance.now()
        : new Date().getTime();

    var scroll = function (timestamp) {
      var currentTime =
        "now" in window.performance
          ? performance.now()
          : new Date().getTime();
      var time = Math.min(1, (currentTime - startTime) / duration);

      window.scrollTo(0, Math.ceil(time * (offsetTop - start) + start));

      if (time < 1) {
        requestAnimationFrame(scroll);
      }
    };

    requestAnimationFrame(scroll);
  }
});
