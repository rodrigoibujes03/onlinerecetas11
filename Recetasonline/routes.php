<?php

require_once __DIR__ . "/app/controllers/RecipeController.php";
require_once __DIR__ . "/app/controllers/AuthController.php";
require_once __DIR__ . "/app/controllers/AdminController.php";

$route = $_GET['route'] ?? 'home';

// CONTROLADORES
$RecipeController = new RecipeController();
$AuthController   = new AuthController();
$AdminController  = new AdminController();

switch ($route) {

    // PUBLIC
    case 'home':
        require __DIR__ . "/app/views/home.php";
        break;

    case 'recipes':
        $RecipeController->list();
        break;

    case 'recipe':
        $RecipeController->single();
        break;

    // AUTH
    case 'login':
        $AuthController->loginForm();
        break;

    case 'login-auth':
        $AuthController->login();
        break;

    case 'register':
        $AuthController->registerForm();
        break;

    case 'register-auth':
        $AuthController->register();
        break;

    case 'logout':
        $AuthController->logout();
        break;

    // USER CRUD
    case 'create-recipe':
        $RecipeController->createForm();
        break;

    case 'save-recipe':
        $RecipeController->save();
        break;

    // ADMIN DASHBOARD
    case 'admin':
        $AdminController->dashboard();
        break;

    case 'admin-recipes':
        $AdminController->recipes();
        break;

    case 'admin-users':
        $AdminController->users();
        break;

    case 'admin-toggle-feature':
        $AdminController->toggleFeatured();
        break;

    case 'admin-toggle-visible':
        $AdminController->toggleVisible();
        break;

    default:
        echo "404 - Página no encontrada";
        break;
}
