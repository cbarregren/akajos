<?php
$baseUrl = '';

function url(string $path): string {
  return '/' . ltrim($path, '/');
}