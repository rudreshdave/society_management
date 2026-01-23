<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    api: __DIR__ . '/../routes/api.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
      'auth.basic' => \App\Http\Middleware\HttpBasicAuthenticationMiddleware::class,
      'CheckUserToken' => \App\Http\Middleware\CheckUserToken::class,
      'ApiAccessHeaderAuthentication' => \App\Http\Middleware\ApiAccessHeaderAuthentication::class,
      'ValidatePostSize' => \App\Http\Middleware\ValidatePostSize::class,
      'CheckPostFileSize' => \App\Http\Middleware\CheckPostFileSize::class,
      'usertype' => \App\Http\Middleware\RoleMiddleware::class,
      'SetDatabaseSociety' => \App\Http\Middleware\SetDatabaseSociety::class,
    ]);
  })
  ->withExceptions(function (Exceptions $exceptions) {
    //
  })->create();
