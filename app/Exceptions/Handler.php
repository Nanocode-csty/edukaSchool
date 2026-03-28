<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    protected $levels = [];
    protected $dontReport = [];
    protected $dontFlash = ['current_password', 'password', 'password_confirmation'];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Manejo personalizado de errores
        $this->renderable(function (Throwable $e, $request) {
            if ($e instanceof NotFoundHttpException) {
                return response()->view('errors.monito-interactivo', ['error' => '404'], 404);
            }

            if ($e instanceof HttpException && $e->getStatusCode() === 403) {
                return response()->view('errors.monito-interactivo', ['error' => '403'], 403);
            }
        });
    }
}
