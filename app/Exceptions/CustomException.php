<?php

namespace App\Exceptions;

use App\Facades\Response;
use Exception;

class CustomException extends Exception
{
    public function __construct(
        public string $msg,
        public int $status,
        public array $errors = []
    ) {
        parent::__construct($this->msg, $this->status);
    }

    public function render()
    {
        return Response::message($this->msg)
            ->status($this->status)
            ->errors($this->errors)
            ->send();
    }

    public function report()
    {
        return false;
    }
}
