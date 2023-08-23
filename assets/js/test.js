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
