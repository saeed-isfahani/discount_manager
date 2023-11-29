<?php

namespace App\Exceptions;

use App\Facades\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    private $customExceptions = [
        ForbiddenAccessException::class,
        BadRequestException::class,
    ];

    private $customErrors = [
        ModelNotFoundException::class => [
            'exception' => NotFoundException::class,
            'message' => ''
        ],
        AuthenticationException::class => [
            'exception' => UnauthorizedException::class,
            'message' => ''
        ],
        RouteNotFoundException::class => [
            'exception' => UnauthorizedException::class,
            'message' => 'auth.errors.token_not_sent'
        ],
        TokenBlacklistedException::class => [
            'exception' => UnauthorizedException::class,
            'message' => 'auth.errors.token_blocked'
        ],
        ValidationException::class => [
            'exception' => BadRequestException::class,
            'message' => 'errors.validation_exception'
        ]
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            $statusCode = $this::getErrorStatusCode($e);
            if (app()->bound('sentry') and $statusCode == 500) {
                app('sentry')->captureException($e);
            }
        });
    }

    public function render($request, Throwable $e)
    {
        $exceptionClass = get_class($e);
        if (in_array($exceptionClass, array_keys($this->customErrors))) {
            $errors = (isset($e->validator) ? $e->validator->getMessageBag()->messages() : []);
            throw new $this->customErrors[$exceptionClass]['exception'](__($this->customErrors[$exceptionClass]['message']), $errors);
        }

        if (in_array($exceptionClass, $this->customExceptions)) return $e->render();

        return Response::message($e->getMessage())
            ->status(500)
            ->send();
    }

    public static function getErrorStatusCode(Throwable $error): int
    {
        return method_exists($error, 'getStatusCode') ? $error->getStatusCode() : 500;
    }
}
