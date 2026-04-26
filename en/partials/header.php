<?php
include __DIR__ . '/config.php';

// Defaults (kan sättas per sida före include)
if (!isset($pageTitle))  { $pageTitle  = "Christer Barregren"; }
if (!isset($activeNav))  { $activeNav  = "start"; }
if (!isset($heroImage))  { $heroImage  = null; }
if (!isset($enUrl))      { $enUrl      = url('en/'); }
if (!isset($svUrl))      { $svUrl      = '/'; }
?>
<!doctype html>
<html lang="sv">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
<link rel="stylesheet" href="<?php echo url('assets/css/site.css'); ?>">
</head>

<body>

<!-- =====================================================
     Topbar
     ===================================================== -->
<header class="topbar">
  <div class="topbar__inner">

    <button
      class="nav-toggle"
      type="button"
      aria-expanded="false"
      aria-controls="mainnav">
      Meny
    </button>

<!-- Meny -->

<nav id="mainnav" class="nav" aria-label="Huvudmeny">

<a href="<?php echo url('/index.php'); ?>"  <?php echo $activeNav === "start"  ? 'aria-current="page"' : ''; ?>>Home  </a>

<a href="<?php echo url('/bocker'); ?>" <?php echo $activeNav === "bocker" ? 'aria-current="page"' : ''; ?>>Books</a>

<a href="<?php echo url('/poems'); ?>" <?php echo $activeNav === "poems" ? 'aria-current="page"' : ''; ?>>Poems</a>

<a href="<?php echo url('/sagas'); ?>"  <?php echo $activeNav === "sagas"  ? 'aria-current="page"' : ''; ?>>Sagas</a>

<a href="<?php echo url('/blog'); ?>" <?php echo $activeNav === "blog" ? 'aria-current="page"' : ''; ?>>Blog</a>

<a href="<?php echo url('/om'); ?>" <?php echo $activeNav === "om" ? 'aria-current="page"' : ''; ?>>About</a>

    </nav>

    <form class="nav-search" action="<?php echo url('sok.php'); ?>" method="get" role="search">
      <label class="nav-search__label" for="nav-search-q">Sök</label>
      <input
        id="nav-search-q"
        class="nav-search__input"
        type="search"
        name="q"
        placeholder="Search"
        aria-label="Search">
    </form>

<div class="nav-lang-wrap">
  <a class="nav-lang" href="<?php echo htmlspecialchars($svUrl, ENT_QUOTES, 'UTF-8'); ?>" title="På svenska" aria-label="Byt till svenska">🇸🇪</a>
  <span class="nav-lang nav-lang--active" aria-current="true" title="In English">🇬🇧</span>
</div>

  </div>
</header>

<!-- =====================================================
     Hero (endast om satt)
     ===================================================== -->
<?php if (!empty($heroImage)): ?>
  <section class="hero" aria-label="Hero">
    <img src="<?php echo url($heroImage); ?>" alt="">
  </section>
<?php endif; ?>

<!-- =====================================================
     Main content
     ===================================================== -->
<main class="content">