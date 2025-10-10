<?php

use App\Controllers\ArticleController;
use App\Controllers\CommentController;
use App\Controllers\AdminController;
use App\Services\Utils;
use App\Library\Router;
use App\Controllers\AuthentificationController;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/config/views.php';
require_once __DIR__ . '/config/_config.php';
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/routes.php';

$router = new Router();
$articleController = new ArticleController();

$routes = $router->registerControllerRoutes($articleController);
$commentController = new CommentController();
$routes = array_merge($routes, $router->registerControllerRoutes($commentController));
$adminController = new AdminController();
$routes = array_merge($routes, $router->registerControllerRoutes($adminController));
$authController = new AuthentificationController();
$routes = array_merge($routes, $router->registerControllerRoutes($authController));

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
