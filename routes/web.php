<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth.redirect'])->name('dashboard');

// Rutas para manejo de errores
Route::get('/unauthorized', [App\Http\Controllers\ErrorController::class, 'unauthorized'])->name('unauthorized');
Route::get('/forbidden', [App\Http\Controllers\ErrorController::class, 'forbidden'])->name('forbidden');
Route::get('/not-found', [App\Http\Controllers\ErrorController::class, 'notFound'])->name('not-found');

// Rutas de ejemplo para demostrar el sistema de autenticación
Route::middleware(['auth.redirect'])->group(function () {
    // Ruta que requiere autenticación
    Route::get('/perfil', function () {
        return view('perfil');
    })->name('perfil');

    // Ruta que requiere rol específico (ejemplo)
    Route::get('/admin', function () {
        return view('admin');
    })->middleware(['role:admin'])->name('admin');
});

require __DIR__ . '/auth.php';
