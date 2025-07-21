<?php

namespace App\Services;

use App\Models\User;
use App\Models\Encuesta;
use App\Models\Respuesta;
use App\Notifications\EncuestaCreated;
use App\Notifications\EncuestaRespondida;
use App\Notifications\EncuestaExpirada;
use App\Events\NotificationSent;
use Illuminate\Support\Facades\Notification;

/**
 * Servicio para manejar las notificaciones del sistema
 * 
 * Responsabilidades:
 * - Enviar notificaciones automáticas
 * - Gestionar notificaciones por eventos
 * - Coordinar notificaciones masivas
 * 
 * Principios aplicados: POO, DRY, KISS, Single Responsibility
 */
class NotificationService
{
    /**
     * Notifica a los usuarios asignados cuando se crea una encuesta
     *
     * @param Encuesta $encuesta
     * @return void
     */
    public function notificarEncuestaCreada(Encuesta $encuesta): void
    {
        // Notificar al creador
        $encuesta->creador->notify(new EncuestaCreated($encuesta));

        // Notificar a las personas asignadas (si tienen usuario)
        $personasConUsuario = $encuesta->personas()
            ->whereHas('user')
            ->with('user')
            ->get();

        foreach ($personasConUsuario as $persona) {
            if ($persona->user) {
                $persona->user->notify(new EncuestaCreated($encuesta));
            }
        }
    }

    /**
     * Notifica al creador cuando se responde una encuesta
     *
     * @param Encuesta $encuesta
     * @param Respuesta $respuesta
     * @return void
     */
    public function notificarRespuestaRecibida(Encuesta $encuesta, Respuesta $respuesta): void
    {
        // Notificar al creador de la encuesta
        $encuesta->creador->notify(new EncuestaRespondida($encuesta, $respuesta));
    }

    /**
     * Notifica cuando una encuesta expira
     *
     * @param Encuesta $encuesta
     * @return void
     */
    public function notificarEncuestaExpirada(Encuesta $encuesta): void
    {
        // Notificar al creador
        $encuesta->creador->notify(new EncuestaExpirada($encuesta));

        // Notificar a administradores (si existen)
        $admins = User::where('role', 'admin')->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new EncuestaExpirada($encuesta));
        }
    }

    /**
     * Notifica a usuarios sobre encuestas que están por expirar
     *
     * @param int $diasAntes
     * @return void
     */
    public function notificarEncuestasPorExpirar(int $diasAntes = 1): void
    {
        $fechaLimite = now()->addDays($diasAntes);

        $encuestasPorExpirar = Encuesta::where('activa', true)
            ->where('fecha_fin', '<=', $fechaLimite)
            ->where('fecha_fin', '>', now())
            ->with('creador')
            ->get();

        foreach ($encuestasPorExpirar as $encuesta) {
            $encuesta->creador->notify(new EncuestaExpirada($encuesta));
        }
    }

    /**
     * Notifica a usuarios sobre encuestas que no han sido respondidas
     *
     * @param int $diasDespues
     * @return void
     */
    public function notificarEncuestasNoRespondidas(int $diasDespues = 3): void
    {
        $fechaLimite = now()->subDays($diasDespues);

        $encuestasNoRespondidas = Encuesta::where('activa', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->whereDoesntHave('respuestas')
            ->with(['creador', 'personas.user'])
            ->get();

        foreach ($encuestasNoRespondidas as $encuesta) {
            // Notificar a personas asignadas que no han respondido
            foreach ($encuesta->personas as $persona) {
                if ($persona->user && !$encuesta->respuestas()->where('user_id', $persona->user->id)->exists()) {
                    $persona->user->notify(new EncuestaCreated($encuesta));
                }
            }
        }
    }

    /**
     * Obtiene estadísticas de notificaciones para un usuario
     *
     * @param User $user
     * @return array
     */
    public function getEstadisticasNotificaciones(User $user): array
    {
        return [
            'total' => $user->notifications()->count(),
            'no_leidas' => $user->unreadNotifications()->count(),
            'leidas' => $user->readNotifications()->count(),
            'hoy' => $user->notifications()->whereDate('created_at', today())->count(),
            'esta_semana' => $user->notifications()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];
    }

    /**
     * Marca todas las notificaciones de un usuario como leídas
     *
     * @param User $user
     * @return void
     */
    public function marcarTodasComoLeidas(User $user): void
    {
        $user->unreadNotifications()->update(['read_at' => now()]);
    }

    /**
     * Elimina notificaciones antiguas (más de 30 días)
     *
     * @param User $user
     * @return int
     */
    public function limpiarNotificacionesAntiguas(User $user): int
    {
        return $user->notifications()
            ->where('created_at', '<', now()->subDays(30))
            ->delete();
    }

    /**
     * Envía una notificación con evento en tiempo real
     *
     * @param User $user
     * @param string $notificationClass
     * @param array $data
     * @return void
     */
    public function enviarNotificacion(User $user, $notificationClass, array $data = []): void
    {
        $notification = new $notificationClass($data);
        $user->notify($notification);

        // Crear registro en la base de datos
        $dbNotification = \App\Models\Notification::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => get_class($notification),
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => $data,
        ]);

        // Disparar evento para notificación en tiempo real
        event(new NotificationSent($dbNotification));
    }
}
