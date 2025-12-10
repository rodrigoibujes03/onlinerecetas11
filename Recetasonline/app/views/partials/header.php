<?php
require_once __DIR__ . '/../../../config/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();
$base = rtrim(BASE_URL,'/');
$uploads_url = UPLOADS_URL;
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>RecetasOnline</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= $base ?>/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="<?= $base ?>/recipes">🍲 RecetasOnline</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMain">
      <form class="d-flex ms-auto me-3" action="<?= $base ?>/search" method="GET">
        <input name="q" class="form-control me-2" type="search" placeholder="Buscar recetas..." aria-label="Buscar">
        <button class="btn btn-light" type="submit">Buscar</button>
      </form>

      <ul class="navbar-nav">
        <?php if(!empty($_SESSION['user'])): ?>
          <li class="nav-item"><a class="nav-link" href="<?= $base ?>/recipe_create">Crear receta</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= $base ?>/myrecipes">Mis recetas</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= $base ?>/favorites">Favoritos</a></li>
          <?php if($_SESSION['user']['role_id']==2): ?>
            <li class="nav-item"><a class="nav-link" href="<?= $base ?>/admin_users">Admin</a></li>
          <?php endif; ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"><?= htmlspecialchars($_SESSION['user']['name']) ?></a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="<?= $base ?>/logout">Cerrar sesión</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="<?= $base ?>/login">Iniciar</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= $base ?>/register">Registrar</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container py-4">
  <?php if(!empty($_SESSION['flash'])): ?>
    <div class="alert alert-info"><?= htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?></div>
  <?php endif; ?>
