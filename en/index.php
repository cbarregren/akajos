<?php

  
$pageTitle = "Christer Barregren - author and poet| Start";
$activeNav = "start";
$heroImage = '/assets/img/hero.webp';
include __DIR__ . "/partials/header.php";
$postsDir = __DIR__ . "/blog/blogg-poster";
require __DIR__ . '/../partials/blogg-loader.php';
$posts = array_values(array_filter($posts, fn($p) => ($p['lang'] ?? 'sv') === 'en'));

/* Bygg utvalda poster för startsidan */
$indexCandidates = array_filter($posts, fn($p) =>
  $p['index_slot'] >= 1 && $p['index_slot'] <= 6
);

$slots = [];
foreach ($indexCandidates as $p) {
  $slots[$p['index_slot']][] = $p;
}

$indexPosts = [];
for ($i = 1; $i <= 6; $i++) {
  if (!empty($slots[$i])) {
    usort($slots[$i], fn($a,$b) => strtotime($b['date']) <=> strtotime($a['date']));
    $indexPosts[] = $slots[$i][0];
  }
}



?>

<section class="section" aria-label="Startinnehåll">
<div class="paper">
<div class="paper__inner">

<div class="intro">
<h1>Christer Barregren</h1>
<p class="subtitle">
YOU MUST SUFFER FOR YOUR ART, OR ELSE YOU SUFFER FOR NOTHING
</p>
</div>
      
<section class="section" aria-label="Pressbild">
<div class="paper">
<div class="paper__inner">


<!-- Pressbild -->

<article class="media-box media-box--centered" style="border-bottom:0;">
  
  <!-- Bild -->
  <a class="media-box__img"
     href="<?php echo url('/assets/img/christer-barregren-1-small.webp'); ?>"
     download>
    <img src="<?php echo url('/assets/img/christer-barregren-1-big.jpg'); ?>"
         alt="Christer Barregren"
         loading="lazy">
  </a>

  <!-- Text -->
  <div class="media-box__content">
    <p class="media-box__text media-box__text--lead"> 
An author and poet with a high ceiling and a long way to the ground. 
He can become possessed by his creative endeavors and is often seen in conversation with mythological pets. 
He escapes only in emergencies and takes no responsibility for others' interpretations of reality. 
He writes poetry to survive. Otherwise, he writes tales. Sometimes, they become books.    </p>
  </div>

</article>

    </div>
  </div>
</section>



<div style="height: 2rem;"></div>



<!-- Visa utvalda blogginlägg -->

<?php if (!empty($indexPosts)): ?>
<section class="section">
  <div class="index-grid index-grid--<?= count($indexPosts) ?>">
    <?php foreach ($indexPosts as $post): ?>
      <?php include __DIR__ . '/../partials/blog-card.php'; ?>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<!-- Slut på Visa utvalda blogginlägg -->
</div>
</div>
</section>
</section>



  
  
<?php include __DIR__ . "/partials/footer.php"; ?>