document.addEventListener("DOMContentLoaded", function () {
  var secondaryNavLinks = document
    .querySelector(".mobile_menu")
    .getElementsByTagName("div");
  var menu = document.querySelector(".mobile_menu");

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

  // Ecouteur pour le dropdown du menu mobile
  const mobileDropdownToggle = document.querySelector('.mobile-dropdown-toggle');
  if (mobileDropdownToggle) { // Si l'élément existe
      mobileDropdownToggle.addEventListener('click', function() {
          const dropdownMenu = this.nextElementSibling;
          if (dropdownMenu) {
              dropdownMenu.classList.toggle('visible'); // Vous pouvez ajouter des classes pour gérer l'affichage comme vous le souhaitez
          }
      });
  }

  // Gérer le redimensionnement de l'écran
  window.addEventListener("resize", closeMenuIfLargeScreen);

  // Appeler la fonction de fermeture du menu au chargement de la page
  closeMenuIfLargeScreen();

  // Fonction pour effectuer l'animation de défilement
  function scrollToElement(element, offsetTop, duration) {
    var start = window.pageYOffset;
    var startTime =
      "now" in window.performance ? performance.now() : new Date().getTime();

    var scroll = function (timestamp) {
      var currentTime =
        "now" in window.performance ? performance.now() : new Date().getTime();
      var time = Math.min(1, (currentTime - startTime) / duration);

      window.scrollTo(0, Math.ceil(time * (offsetTop - start) + start));

      if (time < 1) {
        requestAnimationFrame(scroll);
      }
    };

    requestAnimationFrame(scroll);
  }
});


