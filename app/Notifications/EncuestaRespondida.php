<?php

namespace App\Notifications;

use App\Models\Encuesta;
use App\Models\Respuesta;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EncuestaRespondida extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Encuesta $encuesta,
        public Respuesta $respuesta
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
            ->subject('Nueva Respuesta Recibida: ' . $this->encuesta->titulo)
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Se ha recibido una nueva respuesta para tu encuesta.')
            ->line('Encuesta: ' . $this->encuesta->titulo)
            ->line('Respondida por: ' . $this->respuesta->user->name)
            ->line('Fecha: ' . $this->respuesta->fecha_respuesta->format('d/m/Y H:i'))
            ->action('Ver Respuesta', route('encuestas.show', $this->encuesta))
            ->line('Gracias por usar nuestro sistema.');
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
            'respuesta_id' => $this->respuesta->id,
            'titulo_encuesta' => $this->encuesta->titulo,
            'respondido_por' => $this->respuesta->user->name,
            'fecha_respuesta' => $this->respuesta->fecha_respuesta->toDateTimeString(),
            'tipo' => 'encuesta_respondida',
            'mensaje' => 'Nueva respuesta recibida para: ' . $this->encuesta->titulo,
        ];
    }
}
