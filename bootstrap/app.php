<?php

use App\Exceptions\ServiceException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Exception $e, Request $request) {
            if ($e instanceof ServiceException) {
                $message = $e->getMessage() ?: 'An error occurred while processing your request.';
                return errorJsonResponse($message);
            }

            if ($e instanceof NotFoundHttpException) {
                $message = $e->getMessage() ?: 'Resource not found.';
                return errorJsonResponse($message, statusCode: 404);
            }
        });
        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*'),
        );
    })->create();
