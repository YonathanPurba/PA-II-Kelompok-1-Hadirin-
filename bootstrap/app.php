<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\QueryException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Tambahkan middleware global di sini jika diperlukan
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // $exceptions->render(function (QueryException $e, $request) {
        //     if ($e->getCode() === 2002) {
        //         // tampilkan view kustom jika gagal konek DB
        //         return response()->view('errors.database', [], 500);
        //     }
        // });
    })
    ->create();
