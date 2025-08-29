<?php

namespace Database\Factories;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        $statuses = ['open','pending','closed'];
        $categories = [null, 'billing', 'technical', 'sales', 'account', 'shipping'];

        return [
            'id' => (string) Str::ulid(),
            'subject' => $this->faker->sentence(6),
            'body' => $this->faker->paragraphs(asText: true),
            'status' => $this->faker->randomElement($statuses),
            'category' => $this->faker->randomElement($categories),
            'note' => $this->faker->boolean(40) ? $this->faker->sentence(10) : null,
            'confidence' => $this->faker->boolean(60) ? $this->faker->randomFloat(2, 0.3, 0.99) : null,
            'explanation' => $this->faker->boolean(60) ? $this->faker->sentence(12) : null,
        ];

    }
}
