$(document).ready(function () {
  $(".slider").slider({
    indicators: false,
    height: 800,
  });
  $(".carousel").carousel();
});

// Sélectionnez le menu vertical
var verticalMenu = document.querySelector(".vertical-menu");

// Sélectionnez l'élément contenant le contenu principal
var contentWrapper = document.querySelector(".content-with-fixed-menu");

// Obtenez la position verticale du menu
var menuTopPosition = verticalMenu.offsetTop;

// Fonction de gestion du défilement
function handleScroll() {
  // Obtenez la position verticale actuelle de la page
  var scrollTop = window.pageYOffset || document.documentElement.scrollTop;

  // Vérifiez si la position de défilement dépasse la position du menu
  if (scrollTop > menuTopPosition) {
    // Ajoutez la classe "fixed-menu" au menu
    verticalMenu.classList.add("fixed-menu");
    // Ajoutez une marge supérieure au contenu principal pour compenser l'espace occupé par le menu fixe
    contentWrapper.style.marginTop = "50px";
    // Mettez à jour la hauteur maximale du menu en fonction de la nouvelle hauteur disponible
    verticalMenu.style.maxHeight = `calc(100vh - ${menuTopPosition}px)`;
  } else {
    // Supprimez la classe "fixed-menu" du menu
    verticalMenu.classList.remove("fixed-menu");
    // Réinitialisez la marge supérieure du contenu principal
    contentWrapper.style.marginTop = "";
    // Réinitialisez la hauteur maximale du menu
    verticalMenu.style.maxHeight = "";
  }
}

// Ajoutez un écouteur d'événement de défilement
window.addEventListener("scroll", handleScroll);

document.addEventListener("DOMContentLoaded", function () {
  // Sélectionnez tous les liens du menu
  var links = document.querySelectorAll(".vertical-menu a");

  // Ajoutez un gestionnaire d'événement pour chaque lien
  links.forEach(function (link) {
    link.addEventListener("click", function (event) {
      event.preventDefault();

      // Supprimez la classe active de tous les liens du menu
      links.forEach(function (link) {
        link.classList.remove("active");
      });

      // Ajoutez la classe active à l'élément cliqué
      this.classList.add("active");

      // Obtenez l'ID de l'ancre associée à l'élément cliqué
      var anchorId = this.getAttribute("href").substring(1);

      // Faites défiler jusqu'à l'élément avec l'ID correspondant
      var anchorElement = document.getElementById(anchorId);
      if (anchorElement) {
        anchorElement.scrollIntoView({ behavior: "smooth" });
      }
    });
  });
});
