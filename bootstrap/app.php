<?php

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Logging sécurité pour les dépassements de limite (HTTP 429)
        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            Log::channel('security')->warning('Brute-force / rate limit déclenché', [
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_agent' => $request->userAgent(),
            ]);

            // Ne rien retourner ici => Laravel utilisera la réponse 429 par défaut
            // (page "Too Many Attempts" ou JSON selon le contexte).
        });

        // Tu peux laisser d’autres config ici si Laravel en a généré
    })->create();
