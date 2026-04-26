<?php
$pageTitle  = "Sök";
$activeNav = "";
$heroImage = null;
include __DIR__ . "/partials/header.php";

/* =========================================================
   Helpers
   ========================================================= */

function h(string $s): string {
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function normalize(string $s): string {
  return mb_strtolower(trim($s));
}

function make_excerpt(string $text, int $len = 180): string {
  $text = preg_replace('/\s+/', ' ', trim($text));
  if ($text === '') return '';
  if (mb_strlen($text) <= $len) return $text;
  return mb_substr($text, 0, $len) . "…";
}

/* =========================================================
   1) Innehållsmappar (blogg, böcker, dikter)
   ========================================================= */

function scan_content_dir(string $dir, string $baseUrl, string $type): array {
  if (!is_dir($dir)) return [];
  $out = [];

  foreach (scandir($dir) as $file) {
    if ($file === '.' || $file === '..') continue;
    if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') continue;

    $path = $dir . "/" . $file;
    if (!is_file($path)) continue;

    $data = include $path;
    if (!is_array($data)) continue;

    $title = (string)($data['title'] ?? '');
    $intro = (string)($data['intro'] ?? '');
    $html  = (string)($data['html'] ?? '');
    $img   = (string)($data['img'] ?? '');

    $searchText  = strip_tags($title . " " . $intro . " " . $html);
    $excerptText = strip_tags($intro . " " . $html);

    if (trim($searchText) === '') continue;

    $slug = preg_replace('/^\d+[-_]/', '', pathinfo($file, PATHINFO_FILENAME));

    $out[] = [
      'type'    => $type,
      'title'   => $title !== '' ? $title : $slug,
      'text'    => $searchText,
      'excerpt' => $excerptText,
      'url'     => url($baseUrl . '?slug=' . $slug),
      'img'     => $img
    ];
  }

  return $out;
}

/* =========================================================
   2) Publika sidor i roten (index.php, om-mig.php, framtida)
   ========================================================= */

function scan_root_pages(string $rootDir): array {
  $out = [];

  $ignore = [
    'sok.php',
    'config.php',
    'index.php.bak'
  ];

  foreach (scandir($rootDir) as $file) {
    if ($file === '.' || $file === '..') continue;
    if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') continue;
    if (in_array($file, $ignore, true)) continue;

    $path = $rootDir . '/' . $file;
    if (!is_file($path)) continue;

    $raw = file_get_contents($path);
    if ($raw === false) continue;

    // Rensa PHP, style, script
    $raw = preg_replace('/<\?php.*?\?>/s', '', $raw);
    $raw = preg_replace('/<style\b[^>]*>.*?<\/style>/si', '', $raw);
    $raw = preg_replace('/<script\b[^>]*>.*?<\/script>/si', '', $raw);

    $text = trim(strip_tags($raw));
    if ($text === '') continue;

    $title = pathinfo($file, PATHINFO_FILENAME);
    if ($file === 'index.php') $title = 'Start';

    $out[] = [
      'type'    => 'Sida',
      'title'   => ucfirst($title),
      'text'    => $text,
      'excerpt' => $text,
      'url'     => url($file),
      'img'     => ''
    ];
  }

  return $out;
}

/* =========================================================
   3) Undermappssidor – scannar automatiskt alla undermappar
   ========================================================= */

function scan_subpages(string $rootDir): array {
  $out = [];
  $skip = ['admin', 'assets', 'partials', 'blogg', 'bocker', 'dikter', 'sagor', 'en'];

  foreach (scandir($rootDir) as $dir) {
    if ($dir === '.' || $dir === '..') continue;
    if (in_array($dir, $skip, true)) continue;

    $indexPath = $rootDir . '/' . $dir . '/index.php';
    if (!is_file($indexPath)) continue;

    $raw = file_get_contents($indexPath);
    if ($raw === false) continue;
    $raw = preg_replace('/<\?php.*?\?>/s', '', $raw);
    $raw = preg_replace('/<style\b[^>]*>.*?<\/style>/si', '', $raw);
    $raw = preg_replace('/<script\b[^>]*>.*?<\/script>/si', '', $raw);
    $text = trim(strip_tags($raw));
    if ($text === '') continue;

    $out[] = [
      'type'    => 'Sida',
      'title'   => ucfirst($dir),
      'text'    => $text,
      'excerpt' => $text,
      'url'     => url($dir . '/'),
      'img'     => ''
    ];
  }

  return $out;
}

/* =========================================================
   Kör sökning
   ========================================================= */

$q = trim((string)($_GET['q'] ?? ''));
$results = [];

if ($q !== '') {
  $needle = normalize($q);
  $pool = [];

  $pool = array_merge(
    scan_content_dir(__DIR__ . "/blogg/blogg-poster", "blogg/", "Blogg"),
    scan_content_dir(__DIR__ . "/bocker/bocker-data", "bocker/", "Bok"),
    scan_content_dir(__DIR__ . "/dikter/dikter-data", "dikter/", "Dikt"),
    scan_content_dir(__DIR__ . "/sagor/sagor-data", "sagor/", "Saga"),
    scan_root_pages(__DIR__),
    scan_subpages(__DIR__)
  );

  foreach ($pool as $item) {
    if (mb_strpos(normalize($item['text']), $needle) !== false) {
      $results[] = $item;
    }
  }
}
?>

<section class="section" aria-label="Sök">
  <div class="paper">
    <div class="paper__inner">

      <header class="intro">
        <h1>Sök</h1>
        <p class="subtitle">Hela webbplatsen</p>
      </header>

      <form method="get" class="reading search-form">
        <div class="search-form__row">
          <input
            type="search"
            name="q"
            value="<?php echo h($q); ?>"
            placeholder="Sök i hela webbplatsen…"
            class="search-form__input"
          >
          <button type="submit" class="search-form__button">Sök</button>
        </div>
      </form>

      <?php if ($q !== ''): ?>

        <div class="reading">

          <p class="search-meta">
            <?php
              $count = count($results);
              echo $count . ' ' . ($count === 1 ? 'träff' : 'träffar');
              echo ' på “' . h($q) . '”';
            ?>
          </p>

          <?php if (!$results): ?>
            <p>Inga träffar.</p>
          <?php endif; ?>

          <?php foreach ($results as $r): ?>
            <article class="media-box">
              <div class="media-box__content">
                <p class="blog-meta"><?php echo h($r['type']); ?></p>

                <h2 class="media-box__title">
                  <a href="<?php echo h($r['url']); ?>">
                    <?php echo h($r['title']); ?>
                  </a>
                </h2>

                <p class="media-box__text">
                  <?php echo h(make_excerpt($r['excerpt'])); ?>
                </p>

                <a class="card__link" href="<?php echo h($r['url']); ?>">Visa</a>
              </div>
            </article>
          <?php endforeach; ?>

        </div>

      <?php endif; ?>

    </div>
  </div>
</section>

<?php include __DIR__ . "/partials/footer.php"; ?>