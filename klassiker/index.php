<?php
$pageTitle = "Christer Barregren | Återskapade klassiker";
$activeNav = "klassiker";
$heroImage = null;
include __DIR__ . "/../partials/header.php";

$postsDir = __DIR__ . "/klassiker-data";
$perPage  = 12;

function h(string $s): string {
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function normalize_slug(string $s): string {
  $s = strtolower($s);
  $s = preg_replace('/[^a-z0-9\-]+/', '-', $s);
  return trim($s, '-');
}

function excerpt_from_html(string $html, int $maxChars = 220): string {
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
    if (strtolower(pathinfo($path, PATHINFO_EXTENSION)) !== 'php') continue;

    $base = pathinfo($path, PATHINFO_FILENAME);
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
    $mtime = filemtime($path) ?: time();

    $posts[] = [
      'slug'    => $slug,
      'title'   => $title,
      'html'    => $html,
      'excerpt' => excerpt_from_html($html),
      'order'   => $order,
      'mtime'   => $mtime,
      'tags'    => array_filter(array_map('trim', (array)($data['tags'] ?? []))),
    ];
  }

  usort($posts, function($a, $b) {
    $c = ($b['order'] <=> $a['order']);
    return $c !== 0 ? $c : ($b['mtime'] <=> $a['mtime']);
  });

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

$activeTag = trim((string)($_GET['tag'] ?? ''));
if ($activeTag !== '') {
  $posts = array_values(array_filter($posts, fn($p) =>
    in_array($activeTag, $p['tags'] ?? [], true)
  ));
}

$slug = isset($_GET['slug']) ? normalize_slug((string)$_GET['slug']) : '';
$single = null;
if ($slug !== '') {
  foreach ($posts as $p) {
    if ($p['slug'] === $slug) { $single = $p; break; }
  }
}

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

<section class="section" aria-label="Sagor">
  <div class="paper">
    <div class="paper__inner">

      <?php if ($single): ?>
        <header class="intro">
          <h1><?php echo h($single['title']); ?></h1>
        </header>

        <article class="reading prose">
          <?php echo $single['html']; ?>

          <?php if (!empty($single['tags'])): ?>
            <div class="poem-tags">
              <span class="poem-tags__label">Etiketter:</span>
              <?php foreach ($single['tags'] as $tag): ?>
                <a class="blog-tag" href="/klassiker/?tag=<?php echo urlencode($tag); ?>"><?php echo h($tag); ?></a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <p class="backline">
            <a class="card__link" href="/klassiker/">Tillbaka</a>
          </p>
        </article>

      <?php else: ?>
        <header class="intro">
          <h1>Återskapade klassiker</h1>
          <?php if ($activeTag !== ''): ?>
            <p class="subtitle">Etikett: <strong><?php echo h($activeTag); ?></strong> &nbsp;·&nbsp; <a class="textlink" href="/klassiker/">Visa alla</a></p>
          <?php else: ?>
<p class="subtitle">De stora berättelserna upplevda på nytt </p>
<!--
<p class="subtitle" style="text-align:center; margin:20px 15% 0px 15%; text-transform: none; letter-spacing: 10%; font-size: 16px;">
Återskapade klassiker är koncentrerad litteratur där klassikerna destilleras till sin existentiella kärna. 
Varje återskap bär originalets tonfall, men står samtidigt på egna ben.
</p>
  -->
          <?php endif; ?>
        </header>

        <?php if ($activeTag === ''): ?>
   
        <?php endif; ?>
        </header>

        <?php if ($totalPosts === 0): ?>
          <div class="reading prose">
            <p>Inga klassiker ännu.</p>
          </div>
        <?php else: ?>
          <div class="reading">
            <?php foreach ($postsForPage as $p): ?>
              <article class="card saga-item">
                <div class="card__body">
                  <h2 class="card__title">
                    <a href="/klassiker/?slug=<?php echo h($p['slug']); ?>">
                      <?php echo h($p['title']); ?>
                    </a>
                  </h2>

                  <?php if (!empty($p['tags'])): ?>
                    <p class="blog-tags">
                      <?php foreach ($p['tags'] as $tag): ?>
                        <a class="blog-tag <?php echo $tag === $activeTag ? 'blog-tag--active' : ''; ?>"
                           href="/klassiker/?tag=<?php echo urlencode($tag); ?>"><?php echo h($tag); ?></a>
                      <?php endforeach; ?>
                    </p>
                  <?php endif; ?>

                  <p class="excerpt"><?php echo h($p['excerpt']); ?></p>

                  <a class="card__link" href="/klassiker/?slug=<?php echo h($p['slug']); ?>">Läs</a>
                </div>
              </article>
            <?php endforeach; ?>

            <?php if ($totalPages > 1): ?>
              <nav style="display:flex; align-items:center; justify-content:space-between; gap:14px; margin-top:24px;">
                <div>
                  <?php if ($page > 1): ?>
                    <a class="card__link" href="/klassiker/?page=<?php echo $page - 1; ?>">Föregående</a>
                  <?php endif; ?>
                </div>
                <div style="color:#9a9a9a; font-size:12px; letter-spacing:.12em; text-transform:uppercase;">
                  Sida <?php echo $page; ?> av <?php echo $totalPages; ?>
                </div>
                <div>
                  <?php if ($page < $totalPages): ?>
                    <a class="card__link" href="/klassiker/?page=<?php echo $page + 1; ?>">Nästa</a>
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

<style>
.poem-tags {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 8px;
  margin: 24px 0 18px;
  padding-top: 20px;
  border-top: 1px dashed var(--line, #ddd);
}
.poem-tags__label {
  font-size: 11px;
  letter-spacing: .16em;
  text-transform: uppercase;
  color: #9a9a9a;
  margin-right: 4px;
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
</style>

<?php include __DIR__ . "/../partials/footer.php"; ?>


