<div class="parallax-wrapper">
  <div class="parallax-section promesse">
  
    <!-- <h2>NOTRE PROMESSE</h2> -->
  
    <p class="parallax-content ">
      Pour un voyage au centre de la cuisine caribéenne. <br />
      Te faire vivre une expérience culinaire forte et dépaysante, c'est notre
      mission !
    </p>

    
</div>
  <section class="parallax-section-2">
  <img src="assets/images/soleil.png" alt="image d'un soleil">

  <div class="wrapper ten">
        <div>
            <h3 class="bounce">
                <span>L</span>
                <span>A</span>
                <span>N</span>
                <span>M</span>
                <span>O</span>
                <span>U</span>
                <span>'</span>
                <span> &emsp;  </span>
                <span>S</span>
                <span>O</span>
                <span>L</span>
                <span>è</span>
                <span>Y</span>
                <span> &emsp;  </span>
                <span>P</span>
                <span>L</span>
                <span>é</span>
                <span>Z</span>
                <span>I</span>
    
            </h3>
        </div>
    </div>
  </section>

  <div class="parallax-section">
    
    <div class="parallax-content">
  <!-- MENU ACCUEIL -->

  <?php

include_once 'menu_accueil.php';

  ?>


  <!-- FIN MENU ACCUEIL -->
    </div>
  </div>




  <!-- CONTACT -->

  <section class="section-3" id="contact">
    <div class="contact_form">
      <h2>Laissez-nous vos avis ou commentaires !</h2>
      <form action="/ma-page-de-traitement" method="post">
        <div>
          <input type="text" id="name" name="user_name" placeholder="Nom" />
        </div>
        <div>
          <input type="email" id="email" name="user_mail" placeholder="Email" />
        </div>
        <div>
          <input type="text" id="object" name="object" placeholder="Objet" />
        </div>
        <div>
          <textarea id="msg" name="user_message" placeholder="Votre message"></textarea>
        </div>
        <div class="submit_contact">
          <input type="submit" value="ENVOYER" />
        </div>
      </form>
    </div>
  </section>

  <!-- CONTACT -->
</div>

<script>

   // text animé "promesses"

   var words = [`Lanmou'`, 'Soléy', 'Plézi'],
    part,
    i = 0,
    offset = 0,
    len = words.length,
    forwards = true,
    skip_count = 0,
    skip_delay = 15,
    speed = 120;
var wordflick = function () {
  setInterval(function () {
    if (forwards) {
      if (offset >= words[i].length) {
        ++skip_count;
        if (skip_count == skip_delay) {
          forwards = false;
          skip_count = 0;
        }
      }
    }
    else {
      if (offset == 0) {
        forwards = true;
        i++;
        offset = 0;
        if (i >= len) {
          i = 0;
        }
      }
    }
    part = words[i].substr(0, offset);
    if (skip_count == 0) {
      if (forwards) {
        offset++;
      }
      else {
        offset--;
      }
    }
    $('.word').text(part);
  },speed);
};

$(document).ready(function () {
  wordflick();
});
  
</script>