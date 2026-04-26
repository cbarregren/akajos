<?php
$pageTitle  = "Christer Barregren | Om mig";
$activeNav = "om";
$heroImage = null;

include __DIR__ . "/../partials/header.php";
?>




<section class="section" aria-label="Om Christer Barregren">
  <div class="paper">
    <div class="paper__inner">

      <!-- Intro -->
      <header class="intro">
        <h1>Christer Barregren</h1>
        <p class="subtitle">
          Där går en som tror att han är värd att älskas
         
        </p>
      </header>

      <!-- Start Trivia -->
      <div class="pressblock">

        <!-- Vänster kolumn: bilder -->
        <div class="pressblock__images">
          <a href="<?php echo url('assets/img/christer-barregren-2-big.jpg'); ?>" download>
            <img src="<?php echo url('assets/img/christer-barregren-2-small.webp'); ?>" alt="Pressbild: Christer Barregren" loading="lazy">
          </a>

          <a href="<?php echo url('assets/img/christer-barregren-3-big.jpg'); ?>" download>
            <img src="<?php echo url('assets/img/christer-barregren-3-small.webp'); ?>" alt="Pressbild: Christer Barregren" loading="lazy">
          </a>

          <a href="<?php echo url('assets/img/christer-barregren-1-big.jpg'); ?>" download>
            <img src="<?php echo url('assets/img/christer-barregren-1-small.webp'); ?>" alt="Pressbild: Christer Barregren" loading="lazy">
          </a>

          <a href="<?php echo url('assets/img/christer-barregren-4-big.jpg'); ?>" download>
            <img src="<?php echo url('assets/img/christer-barregren-4-small.webp'); ?>" alt="Pressbild: Christer Barregren" loading="lazy">
          </a>
        </div>

        <!-- Höger kolumn: text -->
        <div class="pressblock__content">
<!--          <h2 class="pressblock__title">Trivia</h2> -->

          <p class="pressblock__text" style="padding-top: 15px">

Författare och poet i själ och hjärta. Det vill säga – när han inte jobbar
för att tjäna pengar och känna sig viktig. Bor för tillfället i Linköping.
Mår som bäst när han är besatt av något kreativt projekt eller går tankspridd
i skogen. Det finaste du kan säga till honom är att han har inspirerat dig.
Det bästa du kan bjuda på är choklad. 
Detta är hans <a href="/programforklaring.php" class="textlink">programforklaring</a>.

          </p>

          <p class="pressblock__text">
            <strong>Ålder:</strong> <span id="christer-age"></span> välförtjänta år<br>
            <strong>Höjd över havet:</strong> 190 opraktiska centimetrar<br>
            <strong>Politisk åskådning:</strong> Greta for president<br>
            <strong>Religiös åskådning:</strong> din gud bestämmer inte över mig<br>
            <strong>Önskar:</strong> fred på jorden<br>
            <strong>Fobi:</strong> sova ensam i hus<br>
            <strong>Civilstånd:</strong> katt<br>
            <strong>Favoritplats:</strong> sängen, skogen<br>
            <strong>Dold talang:</strong> humor<br>
            <strong>Faller för:</strong> ett gott hjärta<br>
            <strong>Livssyn:</strong> rik är den som roas av lite<br>
            <strong>Trail name:</strong> Humming Bear<br>
            <strong>Dricker:</strong> varm choklad<br>
<strong>På gravstenen ska det stå:</strong> Han var en gång<br>
          </p>
        </div>

      </div>
      
      <!-- Slut Trivia -->
      
           
    </div>
    
    
  </div>
  
  
  
</section>



<!-- =====================================================
     Kontakt
     ===================================================== -->
<section class="section" aria-label="Kontakt">
  <div class="paper">
    <div class="paper__inner">

      <header class="intro" style="padding: 18px 0 22px;">
        <h2 style="margin:0; font-size:14px; letter-spacing:.18em; text-transform:uppercase; color:#7a7a7a;">
          Kontakt
        </h2>
      </header>

      <form class="contact" action="https://formspree.io/f/mpqpkelq" method="POST">
        <label class="contact__label">
          Namn
          <input class="contact__input" type="text" name="name" required>
        </label>

        <label class="contact__label">
          E-post
          <input class="contact__input" type="email" name="email" required>
        </label>

        <label class="contact__label">
          Meddelande
          <textarea class="contact__textarea" name="message" rows="6" required></textarea>
        </label>

        <input type="hidden" name="_subject" value="Kontakt från apom.se">
        <input type="hidden" name="_next" value="<?php echo url('om-mig.php?sent=1'); ?>">
        <input class="contact__hp" type="text" name="_gotcha" tabindex="-1" autocomplete="off">

        <button class="buybtn buybtn--primary" type="submit">Skicka</button>
      </form>

      <?php if (!empty($_GET['sent'])): ?>
        <p class="fineprint" style="max-width:70ch; margin:14px auto 0; color:#2f2f2f;">
          Tack! Ditt meddelande har skickats.
        </p>
      <?php endif; ?>

    </div>
  </div>
</section>

<?php include __DIR__ . "/../partials/footer.php"; ?>