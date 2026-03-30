<?php

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('forgot.password');

Route::get('/verification-code', function () {
    return view('auth.verification-code');
})->name('verification.code');

Route::get('/reset-password', function () {
    return view('auth.reset-password');
})->name('reset.password');

Route::get('/capturist/dashboard', function () {
    return view('capturist.dashboard');
})->name('capturist.dashboard');

Route::get('/capturist/categories', function () {
    return view('capturist.categories');
})->name('capturist.categories');

Route::get('/capturist/categories/create', function () {
    return view('capturist.create-category');
})->name('capturist.categories.create');

Route::get('/capturist/assets', function () {
    return view('capturist.assets');
})->name('capturist.assets');

Route::get('/capturist/assets/show', function () {
    return view('capturist.show-asset');
})->name('capturist.assets.show');

Route::get('/capturist/assets/qr', function () {
    return view('capturist.generate-qr');
})->name('capturist.assets.qr');

Route::get('/capturist/assets/create', function () {
    return view('capturist.create-asset');
})->name('capturist.assets.create');

Route::match(['GET', 'POST', 'PUT', 'PATCH', 'DELETE'], '/api-bridge/{path}', function (Request $request, string $path) {
    static $resolvedBase = null;
    static $checkedBases = [];

    $candidateBases = array_values(array_unique(array_filter([
        env('ACTISCAN_API_BASE'),
        env('ACTISCAN_API_V1_BASE'),
        'http://actiscan_fastapi:8001/api',
        'http://actiscan_fastapi:8001/api/v1',
        'http://fastapi:8001/api',
        'http://fastapi:8001/api/v1',
        'http://127.0.0.1:8001/api',
        'http://127.0.0.1:8001/api/v1',
        'http://localhost:8001/api',
        'http://localhost:8001/api/v1',
        'http://host.docker.internal:8001/api',
        'http://host.docker.internal:8001/api/v1',
        'http://127.0.0.1:8000/api',
        'http://localhost:8000/api',
    ])));

    if ($resolvedBase === null) {
        foreach ($candidateBases as $base) {
            $rootBase = preg_replace('/\/api(\/v1)?$/', '', rtrim($base, '/'));
            try {
                $healthResponse = Http::timeout(2)->get($rootBase.'/health');
                $checkedBases[] = $base;
                if ($healthResponse->ok()) {
                    $resolvedBase = rtrim($base, '/');
                    break;
                }
            } catch (\Throwable $e) {
                $checkedBases[] = $base;
            }
        }
    }

    if ($resolvedBase === null) {
        return response()->json([
            'detail' => 'No se encontro una instancia activa de FastAPI.',
            'checked_bases' => $checkedBases,
        ], 503);
    }

    $targetUrl = $resolvedBase.'/'.ltrim($path, '/');
    $method = strtoupper($request->method());
    $query = $request->query();
    $payload = $request->json()->all();
    if (empty($payload)) {
        $payload = $request->except('_token');
    }

    try {
        $response = Http::timeout(12)->send($method, $targetUrl, [
            'query' => $query,
            'json' => $payload,
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'detail' => 'No se pudo contactar a FastAPI desde Laravel.',
            'resolved_base' => $resolvedBase,
            'error' => $e->getMessage(),
        ], 503);
    }

    $contentType = $response->header('content-type', '');
    if (str_contains($contentType, 'application/json')) {
        return response()->json($response->json(), $response->status());
    }

    return response($response->body(), $response->status())
        ->header('content-type', $contentType ?: 'text/plain');
})->where('path', '.*')->withoutMiddleware([VerifyCsrfToken::class]);
