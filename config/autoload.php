<?php

/**
 * Système d'autoload.
 * A chaque fois que PHP va avoir besoin d'une classe, il va appeler cette fonction
 * et chercher dans les divers dossiers (ici models, controllers, views, services) s'il trouve
 * un fichier avec le bon nom. Si c'est le cas, il l'inclut avec require_once.
 */
spl_autoload_register(function($className) {

    $className = str_replace("App\\", "", $className); // On enlève le namespace de base "App\".
    $className = str_replace("\\", "/", $className); // On remplace les backslashs par des slashs.

    // On va chercher le fichier correspondant à la classe.
    $file = __DIR__ . '/../' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
