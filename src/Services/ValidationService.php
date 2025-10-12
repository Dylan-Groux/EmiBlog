<?php

namespace App\Services;

class ValidationService
{
    /**
     * Valide qu'un tableau de chaînes de caractères n'est pas vide.
     * @param array $fields
     * @return bool
     */
    public static function checkNotEmptyFields(array $fields) : void
    {
        foreach ($fields as $field) {
            if (empty(trim($field))) {
                throw new \App\Models\Exceptions\ValidationException("Tous les champs sont requis.");
            }
        }
    }
}

