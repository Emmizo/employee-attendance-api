<?php

namespace App\Services\Exceptions;

use RuntimeException;

class AttendanceConflictException extends RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}

