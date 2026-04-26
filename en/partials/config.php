<?php
// Beräkna base path från partials-mappen (en nivå upp = site-roten)
// Fungerar oavsett om anropande sida ligger i roten eller en undermapp
$siteRoot = dirname(__DIR__);
$docRoot  = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
$baseUrl  = rtrim(str_replace($docRoot, '', $siteRoot), '/');
if ($baseUrl === '.' || $baseUrl === '/') $baseUrl = '';

function url(string $path): string {
  global $baseUrl;
  return $baseUrl . '/' . ltrim($path, '/');
}