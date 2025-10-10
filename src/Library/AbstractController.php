<?php

namespace App\Library;

abstract class AbstractController
{
    public function route(string $action, array $params = []): void
    {
        if (method_exists($this, $action)) {
            call_user_func_array([$this, $action], $params);
        } else {
            throw new \Exception("Méthode $action non trouvée dans le contrôleur " . get_class($this));
        }
    }

    public function handleException(\Exception $e): void
    {
        // Gestion des exceptions, peut être surchargée dans les contrôleurs enfants
        http_response_code(500);
        echo "Une erreur est survenue : " . $e->getMessage();
    }
}
