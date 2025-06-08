<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class notifSelesai implements shouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $message;
    public $pembeliID;
    public $penitipID;
    
    public function __construct($pembeliID, $penitipID, $message)
    {
        $this->message = $message;
        $this->pembeliID = $pembeliID;
        $this->penitipID = $penitipID;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
      public function broadcastOn(): Channel
    {
        // Channel privat untuk user tertentu
        return new PrivateChannel('Pembeli.' . $this->$pembeliID . 'Penitip.' . $this->$penitipID);
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message
        ];
    }

    public function broadcastAs()
    {
        return 'notifikasi.kurir';
    }
}
