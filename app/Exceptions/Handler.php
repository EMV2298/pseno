<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (UnauthorizedException $e, $request) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            return response()->json([
                'status' => 'error',
                'message' => 'Запись не найдена',
            ], 404);
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}