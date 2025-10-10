<?php

use App\Controllers\ArticleController;
use App\Controllers\CommentController;
use App\Controllers\AdminController;
use App\Services\Utils;
use App\Library\Router;

require_once __DIR__ . '/config/_config.php';
require_once __DIR__ . '/vendor/autoload.php';

// On récupère l'action demandée par l'utilisateur.
// Si aucune action n'est demandée, on affiche la page d'accueil.
$action = Utils::request('action', 'showHome');
$router = new Router();
$articleController = new ArticleController();

$routes = $router->registerControllerRoutes($articleController);
$commentController = new CommentController();
$routes = array_merge($routes, $router->registerControllerRoutes($commentController));
$adminController = new AdminController();
$routes = array_merge($routes, $router->registerControllerRoutes($adminController));

// Simule une requête (exemple)
$path = $_GET['route'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$key = $path . ':' . strtoupper($method);

// Dispatch
if (isset($routes[$key])) {
    call_user_func($routes[$key]);
} else {
    echo "Route non trouvée.";
}
