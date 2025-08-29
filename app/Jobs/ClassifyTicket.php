<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Ticket;
use App\Services\TicketClassifier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ClassifyTicket implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Ticket $ticket) {}

    public function handle(TicketClassifier $classifier): void
    {
        $result = $classifier->classify($this->ticket);

        $update = [
            'explanation' => $result['explanation'] ?? null,
            'confidence' => $result['confidence'] ?? null,
        ];

        // Respect manual override: if category already set by user, don't overwrite
        if (empty($this->ticket->category) && !empty($result['category'])) {
            $update['category'] = $result['category'];
        }

        $this->ticket->fill($update)->save();
    }
}
