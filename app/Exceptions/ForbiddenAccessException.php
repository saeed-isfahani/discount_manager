<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class ForbiddenAccessException extends CustomException
{
    public function __construct(string $message , array $errors = [])
    {
        $message = $message ?: 'Forbidden Access';
        throw new CustomException($message, Response::HTTP_FORBIDDEN, $errors);
    }
}
