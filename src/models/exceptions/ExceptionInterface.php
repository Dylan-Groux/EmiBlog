<?php

namespace App\Models\Exceptions;

interface ExceptionInterface {
    public function getMessage(): string;
    public function getCode(): int;
}
