<?php

namespace App\Exceptions;

use Illuminate\Http\Response;


class NotFoundException extends CustomException
{
    public function __construct(string $message, array $errors = [])
    {
        $message = $message ?: 'Not Found';
        throw new CustomException($message, Response::HTTP_NOT_FOUND, $errors);
    }
}
