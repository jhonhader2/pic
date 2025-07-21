<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas de Autenticación
|--------------------------------------------------------------------------
| Aquí se registran todas las rutas relacionadas con la autenticación
| organizadas por funcionalidad siguiendo los principios de 
| POO, DRY, KISS y Single Responsibility
|
*/

// ========================================
// REGISTRO DE USUARIOS
// ========================================
Route::middleware(['auth.redirect', 'activity.logger'])->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->name('register.store');
});

// ========================================
// SESIONES DE USUARIO
// ========================================
Route::middleware(['guest', 'activity.logger'])->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->name('login.store');
});

Route::middleware(['auth.redirect', 'activity.logger'])->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

// ========================================
// RECUPERACIÓN DE CONTRASEÑA
// ========================================
Route::middleware('guest')->group(function () {
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

// ========================================
// VERIFICACIÓN DE EMAIL
// ========================================
Route::middleware(['auth.redirect', 'signed', 'throttle:6,1'])->group(function () {
    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->name('verification.verify');
});

Route::middleware(['auth.redirect', 'throttle:6,1'])->group(function () {
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->name('verification.send');
});
