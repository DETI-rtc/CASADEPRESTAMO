<?php

namespace App\Events;
//use App\Models\Credito;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
//

class CreditoStatusChangedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $credito;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($credito)
    {
        $this->credito = $credito;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
         return new Channel('credito-tracker');      //return ['credito-tracker.' . $this->credito->id, 'credito-tracker'];
    }
}
