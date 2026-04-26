<?php
// Förväntar sig: $post från blogg-loader

$img = $post['img'] ?? '';
$hasImg = trim($img) !== '';
// Stöd både full URL (https://...) och relativ sökväg
$imgSrc = $hasImg ? (preg_match('#^https?://#', $img) ? $img : url($img)) : '';
$postUrl = !empty($post['link_url']) ? $post['link_url'] : '/blogg/?slug=' . urlencode($post['slug']);
?>

<article class="card saga-item">

  <!-- Bild / placeholder -->
  <a href="<?= h($postUrl) ?>"
     class="card__media <?= !$hasImg ? 'media-box__img--ph' : '' ?>">

    <?php if ($hasImg): ?>
      <img src="<?= h($imgSrc) ?>" alt="<?= h($post['title']) ?>">
    <?php else: ?>
      <div class="ph" aria-hidden="true">
        <span><?= h(mb_strtoupper(mb_substr($post['title'], 0, 1))) ?></span>
      </div>
    <?php endif; ?>

  </a>

  <!-- Text -->
  <div class="card__body">

    <p class="blog-meta"><?= h($post['date']) ?></p>

    <h2 class="card__title">
      <a href="<?= h($postUrl) ?>">
        <?= h($post['title']) ?>
      </a>
    </h2>

    <?php if (!empty($post['excerpt'])): ?>
      <p class="card__text excerpt">
        <?= h($post['excerpt']) ?>
      </p>
    <?php endif; ?>

    <a class="card__link" href="<?= h($postUrl) ?>">Läs mer</a>

  </div>
</article>