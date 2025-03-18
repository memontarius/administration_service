<?php

use App\Http\Middleware\CheckUserBan;
use App\Services\ResponseService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'check-ban' => CheckUserBan::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AccessDeniedHttpException $e) {
            ResponseService::abortAsUnauthorized();
        });
        $exceptions->render(function (NotFoundHttpException $e) {
            ResponseService::abortAsNotFound();
        });
        $exceptions->render(function (MethodNotAllowedHttpException $e) {
            ResponseService::abortAsNotFound();
        });
        $exceptions->render(function (AuthenticationException $e) {
            ResponseService::abortAsUnauthenticated();
        });
    })->create();
