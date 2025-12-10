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

// Routes
switch($url) {
    // Public / recipes
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

    // Comments (AJAX)
    case 'comment_post':
        (new CommentController())->post();
        break;

    // Auth
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

    // Admin
    case 'admin_users':
        (new AdminController())->users();
        break;
    case 'admin_delete_user':
        (new AdminController())->deleteUser();
        break;

    default:
        http_response_code(404);
        echo "Página no encontrada (404)";
}
