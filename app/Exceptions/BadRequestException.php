<?php

namespace App\Exceptions;

use Illuminate\Http\Response;


class BadRequestException extends CustomException
{
    public function __construct(string $message, array $errors = [])
    {
        $message = $message ?: 'Bad Request';
        throw new CustomException($message, Response::HTTP_BAD_REQUEST, $errors);
    }
}
