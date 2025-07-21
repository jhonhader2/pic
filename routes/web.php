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
Route::middleware(['auth.redirect', 'activity.logger'])->group(function () {
    // Dashboard principal
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('/dashboard/stats', [App\Http\Controllers\DashboardController::class, 'stats'])
        ->name('dashboard.stats');

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

    // Gestión de encuestas (CRUD)
    Route::resource('encuestas', App\Http\Controllers\EncuestaController::class);
    Route::get('/encuestas/{encuesta}/responder', [App\Http\Controllers\EncuestaController::class, 'responder'])
        ->name('encuestas.responder');
    Route::post('/encuestas/{encuesta}/responder', [App\Http\Controllers\EncuestaController::class, 'storeRespuesta'])
        ->name('encuestas.respuesta.store');

    // Configuración de preguntas
    Route::get('/encuestas/{encuesta}/preguntas/create', [App\Http\Controllers\EncuestaController::class, 'createPreguntas'])
        ->name('encuestas.preguntas.create');
    Route::post('/encuestas/{encuesta}/preguntas', [App\Http\Controllers\EncuestaController::class, 'storePreguntas'])
        ->name('encuestas.preguntas.store');

    // Gestión de notificaciones
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])->name('index');
        Route::patch('/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('markAsRead');
        Route::patch('/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
        Route::delete('/{id}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/clear-read', [App\Http\Controllers\NotificationController::class, 'clearRead'])->name('clearRead');
        Route::get('/unread-count', [App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('unreadCount');
        Route::get('/unread', [App\Http\Controllers\NotificationController::class, 'unread'])->name('unread');
    });
});
