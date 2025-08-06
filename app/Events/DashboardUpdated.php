<?php

namespace App\Events;

use App\Models\Encuesta;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DashboardUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $encuesta;

    /**
     * Create a new event instance.
     */
    public function __construct(Encuesta $encuesta)
    {
        $this->encuesta = $encuesta;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('dashboard'),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'encuesta_id' => $this->encuesta->id,
            'titulo' => $this->encuesta->titulo,
            'tipo' => 'encuesta_creada',
            'mensaje' => 'Se ha creado una nueva encuesta: ' . $this->encuesta->titulo,
            'timestamp' => now()->toISOString(),
        ];
    }
}
