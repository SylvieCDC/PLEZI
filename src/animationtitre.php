<style>
  .wrapper_titre_anime {
    position: relative;
    width: 100%;
    margin: 0 auto;
    max-width: 1000px;
  }

  .wrapper_titre_anime video {
    width: 100%;
  }

  .wrapper_titre_anime svg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  }

  .wrapper_titre_anime svg text {
    font-family: "Cocogoose", sans-serif;
    font-weight: 900;
    text-transform: uppercase;
    font-size: 40px;
  }

  .wrapper_titre_anime svg>rect {
    -webkit-mask: url(#mask);
    mask: url(#mask);
  }

  .wrapper_titre_anime svg rect {
    fill: #FFFAF1;
  }

  .body_titre_anime {
    margin: 60px auto;
  }

  .wrapper_titre_anime:before,
  .wrapper_titre_anime:after {
    content: "";
    position: absolute;
    top: 0;
    width: 10px;
    height: 100%;
    background-color: #FFFAF1;
  }

  .wrapper_titre_anime:before {
    left: -9px;
  }

  .wrapper_titre_anime:after {
    right: -5px;
  }
</style>

<div class="body_titre_anime">
  <div class="wrapper_titre_anime">
    <video autoplay playsinline muted loop preload poster="http://i.imgur.com/xHO6DbC.png">
      <source src="assets/videos/titre.mp4" />
    </video>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 285 80" preserveAspectRatio="xMidYMid slice">
      <defs>
        <mask id="mask" x="0" y="0" width="100%" height="100%">
          <rect x="0" y="0" width="100%" height="100%" />
          <text x="72" y="50">Plézi</text>
        </mask>
      </defs>
      <rect x="0" y="0" width="100%" height="100%" />
    </svg>
  </div>
</div>