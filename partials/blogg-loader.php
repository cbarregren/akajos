<?php
/* =========================================================
   Blogg-loader – gemensam datakälla
   ========================================================= */

// Tillåt anroparen att sätta $postsDir, annars använd default
if (empty($postsDir)) {
  $postsDir = __DIR__ . "/../blogg/blogg-poster";
}

function h(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
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
function format_date_from_string_or_ts(string $date, int $ts): string {
  if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) return $date;
  return date("Y-m-d", $ts);
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

    $mtime = filemtime($path) ?: time();
    $date  = trim((string)($data['date'] ?? ''));
    if ($date === '') $date = date("Y-m-d", $mtime);

    $posts[] = [
      'slug'       => $data['slug'] ?? $slug,
      'order'      => $order,
      'title'      => $data['title']   ?? ucwords(str_replace('-', ' ', $slug)),
      'excerpt'    => $data['excerpt'] ?? excerpt_from_html($data['html'] ?? ''),
      'intro'      => $data['intro']   ?? '',
      'img'        => $data['img']     ?? '',
      'gallery'    => $data['gallery'] ?? [],
      'html'       => $data['html']    ?? '',
      'mtime'      => $mtime,
      'date'       => $date,
      'link_url'   => $data['link_url'] ?? '',
      'index_slot' => (int)($data['index_slot'] ?? 0),
      'tags'       => array_filter(array_map('trim', (array)($data['tags'] ?? []))),
      'lang'       => $data['lang'] ?? 'sv',
    ];
  }

  usort($posts, function($a, $b) {
    $c = ($b['order'] <=> $a['order']);
    return $c !== 0 ? $c : ($b['mtime'] <=> $a['mtime']);
  });

  return $posts;
}

$posts = list_php_posts($postsDir);

// DEBUG – ta bort efter felsökning
if (isset($_GET['debug'])) {
  echo '<pre style="background:#fff;padding:10px;font-size:12px;">';
  echo 'blogLang=' . ($blogLang ?? 'EJ SATT') . "\n";
  echo 'BLOG_LANG=' . (defined('BLOG_LANG') ? BLOG_LANG : 'EJ DEFINIERAD') . "\n";
  foreach ($posts as $p) echo $p['slug'] . ' lang=' . ($p['lang'] ?? '?') . "\n";
  echo '</pre>';
}

// Filtrera på språk – stöd både konstant och variabel
$_activeLang = defined('BLOG_LANG') ? BLOG_LANG : ($blogLang ?? '');
if ($_activeLang !== '') {
  $posts = array_values(array_filter($posts, fn($p) => ($p['lang'] ?? 'sv') === $_activeLang));
}