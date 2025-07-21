<?php

namespace App\Notifications;

use App\Models\Encuesta;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EncuestaCreated extends Notification implements ShouldQueue
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
            ->subject('Nueva Encuesta Creada: ' . $this->encuesta->titulo)
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Se ha creado una nueva encuesta que requiere tu atención.')
            ->line('Título: ' . $this->encuesta->titulo)
            ->line('Descripción: ' . $this->encuesta->descripcion)
            ->line('Fecha de inicio: ' . $this->encuesta->fecha_inicio->format('d/m/Y'))
            ->line('Fecha de fin: ' . $this->encuesta->fecha_fin->format('d/m/Y'))
            ->action('Ver Encuesta', route('encuestas.show', $this->encuesta))
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
            'titulo' => $this->encuesta->titulo,
            'descripcion' => $this->encuesta->descripcion,
            'fecha_inicio' => $this->encuesta->fecha_inicio->toDateString(),
            'fecha_fin' => $this->encuesta->fecha_fin->toDateString(),
            'creador' => $this->encuesta->creador->name,
            'tipo' => 'encuesta_creada',
            'mensaje' => 'Se ha creado una nueva encuesta: ' . $this->encuesta->titulo,
        ];
    }
}
