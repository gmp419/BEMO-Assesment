<?php

namespace App\Console\Commands;

use App\Jobs\ClassifyTicket;
use App\Models\Ticket;
use Illuminate\Console\Command;

class BulkClassifyTickets extends Command
{
    protected $signature = 'tickets:bulk-classify {--limit=50}';
    protected $description = 'Dispatch classification jobs for recent unclassified tickets';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');
        $query = Ticket::query()->whereNull('explanation')->latest()->limit($limit);
        $count = 0;
        $query->each(function (Ticket $t) use (&$count) {
            ClassifyTicket::dispatch($t);
            $count++;
        });

        $this->info("Dispatched {$count} tickets for classification.");
        return self::SUCCESS;
    }
}
