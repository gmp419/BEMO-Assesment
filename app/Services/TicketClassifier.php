<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Ticket;
use ErrorException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use OpenAI\Laravel\Facades\OpenAI;

class TicketClassifier
{
    public function classify(Ticket $ticket): array
    {
        if (!config('openai.classify_enabled')) {
            $categories = ['billing', 'technical', 'sales', 'account', 'shipping'];
            $category = $categories[array_rand($categories)];
            Log::info('TicketClassifier: dummy mode, returning random category', ['category' => $category]);
            return [
                'category' => $category,
                'explanation' => 'Dummy mode: random category for testing.',
                'confidence' => round(mt_rand(60, 98) / 100, 2),
            ];
        }

        $system = 'You are a help-desk ticket classifier. Return ONLY a strict JSON object with keys: "category" (one of: billing, technical, sales, account, shipping), "explanation" (<= 240 chars), and "confidence" (float 0..1). No prose.';
        $user = sprintf("Subject: %s\nBody: %s", $ticket->subject, Str::limit($ticket->body, 4000));

        $resp = OpenAI::chat()->create([
            'model' => config('openai.classify_model', 'gpt-4o-mini'),
            'messages' => [
                ['role' => 'system', 'content' => $system],
                ['role' => 'user', 'content' => $user],
            ],
            'temperature' => 0,
        ]);

        Log::debug('TicketClassifier', collect($resp)->toArray());

        $content = $resp->choices[0]->message->content ?? '{}';

        $json = [];
        if (preg_match('/\{.*\}/s', $content, $m)) {
            $json = json_decode($m[0], true) ?: [];
        }

        return [
            'category' => Arr::get($json, 'category', 'general'),
            'explanation' => Arr::get($json, 'explanation', ''),
            'confidence' => (float) Arr::get($json, 'confidence', '0.0'),
        ];
    }
}
