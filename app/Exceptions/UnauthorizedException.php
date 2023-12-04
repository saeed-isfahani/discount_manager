<?php

namespace App\Exceptions;

use Illuminate\Http\Response;


class UnauthorizedException extends CustomException
{
    public function __construct(string $message, array $errors = [])
    {
        $message = $message ?: 'Unauthorized';
        throw new CustomException($message, Response::HTTP_UNAUTHORIZED, $errors);
    }
}
