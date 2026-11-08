<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'ensure.competition.active' => \App\Http\Middleware\EnsureCompetitionActive::class,
            'stealth.admin'             => \App\Http\Middleware\StealthAdmin::class,
        ]);
        $middleware->trimStrings(except: [
            'vuln_username',
            'vuln_password',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
