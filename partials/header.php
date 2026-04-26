<?php
include __DIR__ . '/config.php';

// Defaults
if (!isset($pageTitle)) { $pageTitle = "Christer Barregren"; }
if (!isset($activeNav)) { $activeNav = "start"; }
if (!isset($heroImage)) { $heroImage = null; }
?>
<!doctype html>
<html lang="sv">

<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?php echo htmlspecialchars($pageTitle); ?></title>

<link rel="stylesheet" href="/assets/css/site.css">

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


<nav id="mainnav" class="nav" aria-label="Huvudmeny">

<a href="/"
<?php if ($activeNav === "start") echo 'aria-current="page"'; ?>>
Start
</a>

<a href="/bocker/"
<?php if ($activeNav === "bocker") echo 'aria-current="page"'; ?>>
Böcker
</a>

<a href="/dikter/"
<?php if ($activeNav === "dikter") echo 'aria-current="page"'; ?>>
Dikter
</a>

<a href="/sagor/"
<?php if ($activeNav === "sagor") echo 'aria-current="page"'; ?>>
Sagor
</a>

<a href="/klassiker/"
<?php if ($activeNav === "klassiker") echo 'aria-current="page"'; ?>>
Klassiker
</a>

<a href="/om/"
<?php if ($activeNav === "om") echo 'aria-current="page"'; ?>>
Om
</a>

</nav>


<form class="nav-search" action="/sok.php" method="get" role="search">

<label class="nav-search__label" for="nav-search-q">Sök</label>

<input
id="nav-search-q"
class="nav-search__input"
type="search"
name="q"
placeholder="Sök"
aria-label="Sök på webbplatsen">

</form>

</div>

</header>


<!-- =====================================================
     Hero
     ===================================================== -->

<?php if (!empty($heroImage)): ?>

<section class="hero">

<img src="/assets/img/hero.webp" alt="">

</section>

<?php endif; ?>


<!-- =====================================================
     Main
     ===================================================== -->

<main class="content">