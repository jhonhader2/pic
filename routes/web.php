<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// =========================
// RUTAS PÚBLICAS
// =========================
Route::get('/', function () {
    return view('welcome');
});

// =========================
// RUTAS DE AUTENTICACIÓN
// =========================
require __DIR__ . '/auth.php';

// =========================
// RUTAS DE ERRORES
// =========================
Route::get('/unauthorized', [App\Http\Controllers\ErrorController::class, 'unauthorized'])->name('unauthorized');
Route::get('/forbidden', [App\Http\Controllers\ErrorController::class, 'forbidden'])->name('forbidden');
Route::get('/not-found', [App\Http\Controllers\ErrorController::class, 'notFound'])->name('not-found');

// =========================
// RUTAS PROTEGIDAS (AUTENTICACIÓN REQUERIDA)
// =========================
Route::middleware(['auth.redirect'])->group(function () {
    // Dashboard principal
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
        ->name('dashboard');

    // Perfil de usuario
    Route::get('/perfil', function () {
        return view('perfil');
    })->name('perfil');

    // Administración (requiere rol específico)
    Route::get('/admin', function () {
        return view('admin');
    })->middleware(['role:admin'])->name('admin');

    // Gestión de personas (CRUD)
    Route::resource('personas', App\Http\Controllers\PersonaController::class);
});
