<?php

namespace App\Models\Exceptions;

use App\Models\Exceptions\NotFoundException;
use App\Models\Exceptions\UnauthorizedException;

class ExceptionManager implements ExceptionHandlerInterface
{
    public function handleException(\Exception $e): void
    {
        if ($e instanceof NotFoundException) {
            http_response_code(404);
            echo "Ressource not found: ";
        } elseif ($e instanceof UnauthorizedException) {
            http_response_code(401);
            echo "Ressource non autorisée: " . $e->getMessage();
        } else {
            http_response_code(500);
            echo "Une erreur est survenue : " . $e->getMessage();
        }
    }
}
