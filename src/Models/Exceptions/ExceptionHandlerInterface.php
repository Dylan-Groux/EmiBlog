<?php

namespace App\Models\Exceptions;

interface ExceptionHandlerInterface
{
    public function handleException(\Exception $e): void;
}
