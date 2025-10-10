<?php

namespace App\Models;

interface ExceptionHandlerInterface
{
    public function handleException(\Exception $e): void;
}
 