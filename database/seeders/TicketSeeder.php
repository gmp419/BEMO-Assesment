<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        Ticket::factory()->count(35)->create();

        Ticket::factory()->create([
            'subject' => 'Cannot log in to dashboard',
            'body' => 'User reports 2FA loop when logging into the dashboard after password reset. Browser: Chrome on Windows.',
            'status' => 'open',
            'note' => 'MFA vendor incident yesterday; retry after cache clear.',
            'category' => 'technical',
            'confidence' => 0.72,
            'explanation' => 'Likely auth session mismatch; technical auth issue.'
        ]);

        Ticket::factory()->create([
            'subject' => 'Invoice double-charged on 8/12',
            'body' => 'Customer states their card was charged twice for order #44911.',
            'status' => 'pending',
            'note' => 'Reconcile with Stripe. Possible delayed capture.',
            'category' => 'billing',
            'confidence' => 0.81,
            'explanation' => 'Keywords suggest billing dispute / double charge.'
        ]);
    }
}
