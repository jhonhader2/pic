<?php

namespace App\Notifications;

use App\Models\Encuesta;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EncuestaExpirada extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Encuesta $encuesta
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Encuesta Expirada: ' . $this->encuesta->titulo)
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Una de tus encuestas ha expirado.')
            ->line('Encuesta: ' . $this->encuesta->titulo)
            ->line('Fecha de expiración: ' . $this->encuesta->fecha_fin->format('d/m/Y'))
            ->line('Total de respuestas: ' . $this->encuesta->total_respuestas)
            ->action('Ver Resultados', route('encuestas.show', $this->encuesta))
            ->line('Puedes revisar los resultados y crear una nueva encuesta si es necesario.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'encuesta_id' => $this->encuesta->id,
            'titulo' => $this->encuesta->titulo,
            'fecha_expiracion' => $this->encuesta->fecha_fin->toDateString(),
            'total_respuestas' => $this->encuesta->total_respuestas,
            'tipo' => 'encuesta_expirada',
            'mensaje' => 'La encuesta ha expirado: ' . $this->encuesta->titulo,
        ];
    }
}
