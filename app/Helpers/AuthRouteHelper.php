<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;

/**
 * Clase Helper para Rutas de Autenticación
 * 
 * Esta clase centraliza la lógica de creación de rutas de autenticación
 * siguiendo los principios de POO, DRY, KISS y Single Responsibility
 */
class AuthRouteHelper
{
    /**
     * Configuración de middleware y grupos
     */
    private static array $config;

    /**
     * Inicializar la configuración
     */
    public static function init(): void
    {
        self::$config = require base_path('routes/auth-config.php');
    }

    /**
     * Registrar rutas de registro de usuarios
     */
    public static function registerRoutes(): void
    {
        Route::middleware(self::$config['guest_middleware'])->group(function () {
            Route::post('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store'])
                ->name('register');
        });
    }

    /**
     * Registrar rutas de sesión (login/logout)
     */
    public static function sessionRoutes(): void
    {
        // Rutas para usuarios invitados
        Route::middleware(self::$config['guest_middleware'])->group(function () {
            Route::post('/login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store'])
                ->name('login');
        });

        // Rutas para usuarios autenticados
        Route::middleware(self::$config['auth_middleware'])->group(function () {
            Route::post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
        });
    }

    /**
     * Registrar rutas de recuperación de contraseña
     */
    public static function passwordResetRoutes(): void
    {
        Route::middleware(self::$config['guest_middleware'])->group(function () {
            Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])
                ->name('password.email');

            Route::post('/reset-password', [\App\Http\Controllers\Auth\NewPasswordController::class, 'store'])
                ->name('password.store');
        });
    }

    /**
     * Registrar rutas de verificación de email
     */
    public static function emailVerificationRoutes(): void
    {
        Route::middleware(self::$config['verification_middleware'])->group(function () {
            Route::get('/verify-email/{id}/{hash}', \App\Http\Controllers\Auth\VerifyEmailController::class)
                ->name('verification.verify');
        });

        Route::middleware(self::$config['notification_middleware'])->group(function () {
            Route::post('/email/verification-notification', [\App\Http\Controllers\Auth\EmailVerificationNotificationController::class, 'store'])
                ->name('verification.send');
        });
    }

    /**
     * Registrar todas las rutas de autenticación
     */
    public static function registerAllRoutes(): void
    {
        self::init();
        self::registerRoutes();
        self::sessionRoutes();
        self::passwordResetRoutes();
        self::emailVerificationRoutes();
    }
}
