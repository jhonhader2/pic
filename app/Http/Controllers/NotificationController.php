<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Constructor con inyección de dependencias
     */
    public function __construct(
        private NotificationService $notificationService
    ) {}

    /**
     * Muestra la lista de notificaciones del usuario
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = $this->notificationService->getEstadisticasNotificaciones($user);

        if ($request->expectsJson()) {
            return response()->json([
                'notifications' => $notifications,
                'stats' => $stats,
            ]);
        }

        return view('notifications.index', compact('notifications', 'stats'));
    }

    /**
     * Marca una notificación como leída
     */
    public function markAsRead(string $id): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notificación marcada como leída',
        ]);
    }

    /**
     * Marca todas las notificaciones como leídas
     */
    public function markAllAsRead(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $this->notificationService->marcarTodasComoLeidas($user);

        return response()->json([
            'success' => true,
            'message' => 'Todas las notificaciones marcadas como leídas',
        ]);
    }

    /**
     * Elimina una notificación
     */
    public function destroy(string $id): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notificación eliminada',
        ]);
    }

    /**
     * Elimina todas las notificaciones leídas
     */
    public function clearRead(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $deleted = $user->readNotifications()->delete();

        return response()->json([
            'success' => true,
            'message' => "{$deleted} notificaciones eliminadas",
        ]);
    }

    /**
     * Obtiene el contador de notificaciones no leídas
     */
    public function unreadCount(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $count = $user->unreadNotifications()->count();

        return response()->json([
            'count' => $count,
        ]);
    }

    /**
     * Obtiene las notificaciones no leídas para el dropdown
     */
    public function unread(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $notifications = $user->unreadNotifications()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'notifications' => $notifications,
        ]);
    }
}
