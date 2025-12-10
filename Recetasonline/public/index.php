<?php
// public/index.php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/helpers/db.php';

// Autoload controllers/models
spl_autoload_register(function($class){
    $paths = [
        __DIR__ . '/../app/controllers/' . $class . '.php',
        __DIR__ . '/../app/models/' . $class . '.php'
    ];
    foreach($paths as $p) if (file_exists($p)) require_once $p;
});

$base = rtrim(BASE_URL, '/');
$url = trim($_GET['url'] ?? 'recipes', '/');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recetas Online</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fa;
            font-family: "Segoe UI", sans-serif;
        }
        .navbar {
            background: #0d6efd;
            padding: 12px 0;
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
            color: white !important;
        }
        .nav-link {
            color: #e8e8e8 !important;
            font-weight: 500;
        }
        .nav-link:hover {
            color: #ffffff !important;
        }
        .wrapper {
            max-width: 1100px;
            margin: 25px auto;
        }
        .content-box {
            background: white;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.08);
        }
    </style>
</head>

<body>

<!-- NAVBAR LIMPIA Y PROFESIONAL -->
<nav class="navbar navbar-expand-lg">
    <div class="container">

        <a class="navbar-brand" href="<?= BASE_URL ?>/recipes">Recetas Online</a>

        <button class="navbar-toggler bg-light" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/recipes">Inicio</a>
                </li>

                <?php if (!empty($_SESSION['user'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/myrecipes">Mis recetas</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/favorites">Favoritos</a>
                    </li>

                    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link fw-bold text-warning" href="<?= BASE_URL ?>/admin_users">Administración</a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav">
                <!-- SI EL LOGIN Y REGISTRO YA APARECEN EN LA VISTA, NO LOS MOSTRAMOS AQUÍ -->
                <?php if (empty($_SESSION['user'])): ?>

                    <?php if ($url !== "login" && $url !== "register"): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/login">Iniciar sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/register">Registrarse</a>
                        </li>
                    <?php endif; ?>

                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/logout">Cerrar sesión</a>
                    </li>
                <?php endif; ?>
            </ul>

        </div>
    </div>
</nav>

<!-- CONTENIDO -->
<div class="wrapper">
    <div class="content-box">

<?php
// ROUTER ORIGINAL SIN CAMBIOS
switch($url) {

    case '':
    case 'recipes':
        (new RecipeController())->list();
        break;

    case 'recipe':
        (new RecipeController())->show();
        break;

    case 'recipe_create':
        (new RecipeController())->createView();
        break;

    case 'recipe_save':
        (new RecipeController())->save();
        break;

    case 'recipe_edit':
        (new RecipeController())->editView();
        break;

    case 'recipe_update':
        (new RecipeController())->update();
        break;

    case 'recipe_delete':
        (new RecipeController())->delete();
        break;

    case 'myrecipes':
        (new RecipeController())->myRecipes();
        break;

    case 'favorites':
        (new RecipeController())->favoritesView();
        break;

    case 'favorite_toggle':
        (new RecipeController())->toggleFavorite();
        break;

    case 'search':
        (new RecipeController())->list();
        break;

    case 'comment_post':
        (new CommentController())->post();
        break;

    case 'login':
        (new AuthController())->loginView();
        break;

    case 'login_post':
        (new AuthController())->loginPost();
        break;

    case 'register':
        (new AuthController())->registerView();
        break;

    case 'register_post':
        (new AuthController())->registerPost();
        break;

    case 'logout':
        (new AuthController())->logout();
        break;

    case 'admin_users':
        (new AdminController())->users();
        break;

    case 'admin_delete_user':
        (new AdminController())->deleteUser();
        break;

    default:
        http_response_code(404);
        echo "<h3 class='text-danger'>⚠ Página no encontrada (404)</h3>";
}
?>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
