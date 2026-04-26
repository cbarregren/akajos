<?php
$pageTitle = "Christer Barregren | Böcker";
$activeNav = "bocker";
$heroImage = null;
include __DIR__ . "/../partials/header.php";

$postsDir = __DIR__ . "/bocker-data";
$perPage  = 12;

function h(string $s): string {
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function normalize_slug(string $s): string {
  $s = strtolower($s);
  $s = preg_replace('/[^a-z0-9\-]+/', '-', $s);
  return trim($s, '-');
}

function excerpt_from_html(string $html, int $maxChars = 240): string {
  $text = trim(preg_replace('/\s+/', ' ', strip_tags($html)));
  if (mb_strlen($text) <= $maxChars) return $text;
  return mb_substr($text, 0, $maxChars) . "…";
}

function list_php_posts(string $postsDir): array {
  if (!is_dir($postsDir)) return [];

  $files = scandir($postsDir);
  if ($files === false) return [];

  $posts = [];

  foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;

    $path = $postsDir . "/" . $file;
    if (!is_file($path)) continue;

    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    if ($ext !== 'php') continue;

    $base = pathinfo($path, PATHINFO_FILENAME);

    // Filnamn: N-slug (N = heltal i början)
    $order = 0;
    $slugFromName = $base;

    if (preg_match('/^(\d+)[\-_](.+)$/', $base, $m)) {
      $order = (int)$m[1];
      $slugFromName = $m[2];
    }

    $slug = normalize_slug($slugFromName);

    $data = include $path;
    if (!is_array($data)) continue;

    $title = isset($data['title']) && trim((string)$data['title']) !== ''
      ? (string)$data['title']
      : ucwords(str_replace('-', ' ', $slug));

    $html = isset($data['html']) ? (string)$data['html'] : '';

    // excerpt: använd fält om det finns, annars bygg från html
    $excerpt = isset($data['excerpt']) && trim((string)$data['excerpt']) !== ''
      ? (string)$data['excerpt']
      : excerpt_from_html($html);

    // intro: ingress under rubriken i single-vy (valfritt)
    $intro = isset($data['intro']) && trim((string)$data['intro']) !== ''
      ? (string)$data['intro']
      : '';

    // subtitle: valfritt (om du använder det)
    $subtitle = isset($data['subtitle']) && trim((string)$data['subtitle']) !== ''
      ? (string)$data['subtitle']
      : '';

    // bild: krävs för listlayouten
    $img = isset($data['img']) ? trim((string)$data['img']) : '';
    if ($img === '') {
      // Om du vill tillåta böcker utan bild: ta bort denna continue.
      continue;
    }

    $mtime = filemtime($path) ?: time();

    $posts[] = [
      'slug'     => $slug,
      'order'    => $order,
      'title'    => $title,
      'subtitle' => $subtitle,
      'excerpt'  => $excerpt,
      'intro'    => $intro,
      'img'      => $img,
      'html'     => $html,
      'mtime'    => $mtime,
    ];
  }

  // Sortering: högsta order först (1 längst ner). Vid lika: nyast ändrade först.
  usort($posts, function($a, $b) {
    $c = ($b['order'] <=> $a['order']);
    if ($c !== 0) return $c;
    return ($b['mtime'] <=> $a['mtime']);
  });

  // Unika slugs (behåll högst upp efter sortering)
  $seen = [];
  $unique = [];
  foreach ($posts as $p) {
    if (isset($seen[$p['slug']])) continue;
    $seen[$p['slug']] = true;
    $unique[] = $p;
  }

  return $unique;
}

$posts = list_php_posts($postsDir);

// Enskild post
$slug = isset($_GET['slug']) ? normalize_slug((string)$_GET['slug']) : '';
$single = null;
if ($slug !== '') {
  foreach ($posts as $p) {
    if ($p['slug'] === $slug) { $single = $p; break; }
  }
}

// Pagination i listläge
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
?>

<section class="section" aria-label="Böcker">
  <div class="paper">
    <div class="paper__inner">

      <?php if ($single): ?>

        <header class="intro">
          <h1><?php echo h($single['title']); ?></h1>

          <?php if (!empty($single['intro'])): ?>
            <!-- Samma “under rubriken”-stil som index: vanlig <p> utan klass -->
            <p><?php echo h($single['intro']); ?></p>
          <?php endif; ?>

          <?php if (!empty($single['subtitle'])): ?>
            <p class="subtitle"><?php echo h($single['subtitle']); ?></p>
          <?php endif; ?>
        </header>

        <div class="reading prose">
          <?php echo $single['html']; ?>
        </div>

        

        <p class="backline" style="text-align:center;">
          <a class="card__link" href="<?php echo url('bocker/'); ?>">Fler böcker</a>
        </p>

      <?php else: ?>

        <header class="intro">
          <h1>Böcker</h1>
          <p class="subtitle">
Vägen till verklighetens undermedvetna
</p> 
        </header>

        <?php if ($totalPosts === 0): ?>
          <div class="reading prose">
            <p>Inga böcker ännu.</p>
          </div>
        <?php else: ?>

          <div class="reading">
            <?php foreach ($postsForPage as $p): ?>
              <article class="media-box">
                <a class="media-box__img" href="index.php?slug=<?php echo h($p['slug']); ?>">
                  <img src="<?php echo h(url($p['img'])); ?>" alt="<?php echo h($p['title']); ?>">
                </a>

                <div class="media-box__content">
                  <h2 class="media-box__title">
                    <a href="index.php?slug=<?php echo h($p['slug']); ?>">
                      <?php echo h($p['title']); ?>
                    </a>
                  </h2>

                  <?php if (!empty($p['excerpt'])): ?>
                    <p class="media-box__text"><?php echo h($p['excerpt']); ?></p>
                  <?php endif; ?>

                  <a class="card__link" href="index.php?slug=<?php echo h($p['slug']); ?>">Läs mer</a>
                </div>
              </article>
            <?php endforeach; ?>

            <?php if ($totalPages > 1): ?>
              <nav style="display:flex; align-items:center; justify-content:space-between; gap:14px; margin-top:24px;">
                <div>
                  <?php if ($page > 1): ?>
                    <a class="card__link" href="<?php echo url('bocker/'); ?>?page=<?php echo $page - 1; ?>">Föregående</a>
                  <?php endif; ?>
                </div>

                <div style="color:#9a9a9a; font-size:12px; letter-spacing:.12em; text-transform:uppercase;">
                  Sida <?php echo (int)$page; ?> av <?php echo (int)$totalPages; ?>
                </div>

                <div>
                  <?php if ($page < $totalPages): ?>
                    <a class="card__link" href="<?php echo url('bocker/'); ?>?page=<?php echo $page + 1; ?>">Nästa</a>
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

<?php include __DIR__ . "/../partials/footer.php"; ?>