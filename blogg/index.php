<?php
$pageTitle = "Christer Barregren | Blogg";
$activeNav = "blogg";
include __DIR__ . "/../partials/header.php";
$blogLang = 'sv';
require __DIR__ . '/../partials/blogg-loader.php';
$perPage  = 12;

// =========================================================
// Single-post logik
// =========================================================

$slug = isset($_GET['slug']) ? normalize_slug((string)$_GET['slug']) : '';
$single = null;

if ($slug !== '') {
  foreach ($posts as $p) {
    if ($p['slug'] === $slug) {
      $single = $p;
      break;
    }
  }
}

// =========================================================
// Tag-filtrering
// =========================================================

$activeTag = trim((string)($_GET['tag'] ?? ''));
if ($activeTag !== '') {
  $posts = array_values(array_filter($posts, fn($p) =>
    in_array($activeTag, $p['tags'] ?? [], true)
  ));
}

// =========================================================
// Pagination (endast om inte single)
// =========================================================

if (!$single) {
  $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

  $totalPosts = count($posts);
  $totalPages = (int) ceil($totalPosts / $perPage);

  $offset = ($page - 1) * $perPage;
  $postsForPage = array_slice($posts, $offset, $perPage);

  if ($totalPages > 0 && $page > $totalPages) {
    $page = $totalPages;
    $offset = ($page - 1) * $perPage;
    $postsForPage = array_slice($posts, $offset, $perPage);
  }
}


?>

<link rel="stylesheet" href="<?php echo url('assets/css/blogg.css'); ?>">

<section class="section" aria-label="Blogg">
  <div class="paper">
    <div class="paper__inner">

      <?php if ($single): ?>

        <div class="blog-single">

          <div class="blog-single__header">
            <?php if (!empty($single['date'])): ?>
              <p class="blog-single__date"><?php echo h($single['date']); ?></p>
            <?php endif; ?>

            <h1 class="blog-single__title"><?php echo h($single['title']); ?></h1>

            <?php if (!empty($single['intro'])): ?>
              <p class="blog-single__intro"><?php echo h($single['intro']); ?></p>
            <?php endif; ?>

            <?php if (!empty($single['tags'])): ?>
              <div class="poem-tags" style="justify-content:center;">
                <span class="poem-tags__label">Etiketter:</span>
                <?php foreach ($single['tags'] as $tag): ?>
                  <a class="blog-tag" href="/blogg/?tag=<?php echo urlencode($tag); ?>"><?php echo h($tag); ?></a>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>

          <?php
            $gallery = $single['gallery'] ?? [];
            $hasGallery = is_array($gallery) && count($gallery) > 0;
            $hasHero = !empty($single['img']);
            $heroSrc = $hasHero ? (preg_match('#^https?://#', $single['img']) ? $single['img'] : url($single['img'])) : '';
          ?>

          <?php if ($hasGallery): ?>
            <div class="blog-carousel" data-carousel>
              <button class="car-btn car-btn--prev" type="button" data-prev aria-label="Föregående bild">‹</button>
              <div class="car-viewport" data-viewport>
                <div class="car-track" data-track>
                  <?php foreach ($gallery as $g): ?>
                    <figure class="car-slide">
                      <?php $gSrc = preg_match('#^https?://#', $g) ? $g : url($g); ?>
                      <img src="<?php echo h($gSrc); ?>" alt="">
                    </figure>
                  <?php endforeach; ?>
                </div>
              </div>
              <button class="car-btn car-btn--next" type="button" data-next aria-label="Nästa bild">›</button>
              <div class="car-dots" data-dots aria-label="Bildnavigering"></div>
            </div>

          <?php elseif ($hasHero): ?>
            <figure class="blog-single__hero">
              <img src="<?php echo h($heroSrc); ?>" alt="<?php echo h($single['title']); ?>">
            </figure>
          <?php endif; ?>

          <div class="blog-single__body reading prose">
            <?php echo $single['html']; ?>
          </div>

          <div class="blog-single__foot">
            <a class="card__link" href="/blogg/">← Fler inlägg</a>
          </div>

        </div>

      <?php else: ?>

        <header class="intro">
          <h1>Blogg</h1>
          <?php if ($activeTag !== ''): ?>
            <p class="subtitle">Etikett: <strong><?php echo h($activeTag); ?></strong> &nbsp;·&nbsp; <a class="textlink" href="/blogg/">Visa alla</a></p>
          <?php else: ?>
            <p class="subtitle">Noteringar, utkast och fragment</p>
          <?php endif; ?>
        </header>

        <?php if ($totalPosts === 0): ?>
          <div class="reading prose">
            <p>Inga inlägg ännu.</p>
          </div>
        <?php else: ?>

          <div class="reading">
            <?php foreach ($postsForPage as $p): ?>
              <?php $postUrl = !empty($p['link_url']) ? $p['link_url'] : '/blogg/?slug=' . urlencode($p['slug']); ?>
              <article class="media-box media-box--blog">

                <a class="media-box__img <?php echo empty($p['img']) ? 'media-box__img--ph' : ''; ?>"
                   href="<?php echo h($postUrl); ?>">

                  <?php if (!empty($p['img'])): ?>
                    <?php $imgSrc = preg_match('#^https?://#', $p['img']) ? $p['img'] : url($p['img']); ?>
                    <img src="<?php echo h($imgSrc); ?>" alt="<?php echo h($p['title']); ?>">
                  <?php else: ?>
                    <div class="ph" aria-hidden="true">
                      <span><?php echo h(mb_strtoupper(mb_substr($p['title'], 0, 1))); ?></span>
                    </div>
                  <?php endif; ?>

                </a>

                <div class="media-box__content">
                  <p class="blog-meta"><?php echo h($p['date']); ?></p>

                  <?php if (!empty($p['tags'])): ?>
                    <p class="blog-tags">
                      <?php foreach ($p['tags'] as $tag): ?>
                        <a class="blog-tag <?php echo $tag === $activeTag ? 'blog-tag--active' : ''; ?>"
                           href="/blogg/?tag=<?php echo urlencode($tag); ?>">
                          <?php echo h($tag); ?>
                        </a>
                      <?php endforeach; ?>
                    </p>
                  <?php endif; ?>

                  <h2 class="media-box__title">
                    <a href="<?php echo h($postUrl); ?>">
                      <?php echo h($p['title']); ?>
                    </a>
                  </h2>

                  <?php if (!empty($p['excerpt'])): ?>
                    <p class="media-box__text"><?php echo h($p['excerpt']); ?></p>
                  <?php endif; ?>

                  <a class="card__link" href="<?php echo h($postUrl); ?>">Läs mer</a>
                </div>

              </article>
            <?php endforeach; ?>

            <?php if ($totalPages > 1): ?>
              <nav class="blog-pager">
                <div>
                  <?php if ($page > 1): ?>
                    <a class="card__link" href="/blogg/?page=<?php echo $page - 1; ?>">Föregående</a>
                  <?php endif; ?>
                </div>

                <div class="blog-pager__meta">
                  Sida <?php echo (int)$page; ?> av <?php echo (int)$totalPages; ?>
                </div>

                <div>
                  <?php if ($page < $totalPages): ?>
                    <a class="card__link" href="/blogg/?page=<?php echo $page + 1; ?>">Nästa</a>
                  <?php endif; ?>
                </div>
              </nav>
            <?php endif; ?>

          </div>

        <?php endif; ?>

      <?php endif; ?>

    </div>
  </div>
</section>

<script>
(function(){
  const root = document.querySelector('[data-carousel]');
  if (!root) return;

  const track = root.querySelector('[data-track]');
  const slides = Array.from(root.querySelectorAll('.car-slide'));
  const prev = root.querySelector('[data-prev]');
  const next = root.querySelector('[data-next]');
  const dotsWrap = root.querySelector('[data-dots]');
  if (!track || slides.length === 0) return;

  let i = 0;

  function renderDots(){
    if (!dotsWrap) return;
    dotsWrap.innerHTML = '';
    slides.forEach((_, idx) => {
      const b = document.createElement('button');
      b.type = 'button';
      b.className = 'car-dot' + (idx === i ? ' is-active' : '');
      b.addEventListener('click', () => go(idx));
      dotsWrap.appendChild(b);
    });
  }

function go(idx){
  i = Math.max(0, Math.min(slides.length - 1, idx));
  track.style.transform = `translateX(${(-i * 100)}%)`;

  // Tona ned pilar när de inte behövs
  if (prev) prev.disabled = (i === 0);
  if (next) next.disabled = (i === slides.length - 1);

  renderDots();
}

prev && prev.addEventListener('click', () => go(i - 1));
next && next.addEventListener('click', () => go(i + 1));

  // swipe
  let startX = null;
  root.addEventListener('touchstart', (e) => { startX = e.touches[0].clientX; }, {passive:true});
  root.addEventListener('touchend', (e) => {
    if (startX === null) return;
    const endX = e.changedTouches[0].clientX;
    const dx = endX - startX;
    startX = null;
    if (Math.abs(dx) < 40) return;
    if (dx < 0) go(i + 1); else go(i - 1);
  }, {passive:true});

  go(0);
})();
</script>

<?php include __DIR__ . "/../partials/footer.php"; ?>

<style>
/* =====================
   Single post – modern
   ===================== */
.blog-single {
  max-width: 72ch;
  margin: 0 auto;
}

.blog-single__header {
  text-align: center;
  padding: 32px 0 28px;
  border-bottom: 1px dashed var(--line, #ddd);
  margin-bottom: 32px;
}

.blog-single__date {
  margin: 0 0 14px;
  font-size: 11px;
  letter-spacing: .20em;
  text-transform: uppercase;
  color: #b0b0b0;
}

.blog-single__title {
  margin: 0 0 16px;
  font-size: clamp(28px, 4vw, 46px);
  font-weight: 600;
  letter-spacing: .02em;
  color: #4a4a4a;
  line-height: 1.15;
}

.blog-single__intro {
  margin: 0 auto 20px;
  max-width: 54ch;
  font-size: 17px;
  line-height: 1.75;
  color: #7a7a7a;
  font-style: italic;
}

.blog-single__hero {
  margin: 0 0 32px;
  border: 1px dashed var(--line, #ddd);
  background: #fff;
  overflow: hidden;
}

.blog-single__hero img {
  width: 100%;
  height: auto;
  display: block;
}

.blog-single__body {
  margin-bottom: 40px;
}

.blog-single__foot {
  padding-top: 24px;
  border-top: 1px dashed var(--line, #ddd);
  text-align: center;
}

.poem-tags {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 8px;
  margin: 16px 0 0;
  justify-content: center;
}

.poem-tags__label {
  font-size: 11px;
  letter-spacing: .16em;
  text-transform: uppercase;
  color: #b0b0b0;
  margin-right: 2px;
}

.blog-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
  margin: 6px 0 10px;
}

.blog-tag {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 999px;
  border: 1px solid rgba(0,0,0,.14);
  background: #fff;
  color: #7a7a7a;
  font-size: 11px;
  letter-spacing: .12em;
  text-transform: uppercase;
  text-decoration: none;
  transition: background .15s, color .15s;
}

.blog-tag:hover, .blog-tag--active {
  background: #2f2f2f;
  border-color: #2f2f2f;
  color: #fff;
}

.admin-btn--danger {
  background: #fff;
  border-color: #c0392b;
  color: #c0392b !important;
}

.admin-btn--danger:hover {
  background: #c0392b;
  border-color: #c0392b;
  color: #fff !important;
}
</style>