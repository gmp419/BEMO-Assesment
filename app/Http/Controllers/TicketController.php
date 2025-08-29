<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Jobs\ClassifyTicket;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $q        = (string) $request->query('q', '');
        $status   = (string) $request->query('status', '');
        $category = (string) $request->query('category', '');
        $perPage  = (int) $request->integer('per_page', 10);

        $query = Ticket::query();

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('subject', 'like', "%{$q}%")
                    ->orWhere('body', 'like', "%{$q}%");
            });
        }
        if ($status !== '') {
            $query->where('status', $status);
        }
        if ($category !== '') {
            $query->where('category', $category);
        }

        $tickets = $query->orderByDesc('created_at')->paginate($perPage);
        return response()->json($tickets);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'subject' => ['required','string','max:255'],
            'body' => ['required','string'],
            'status' => ['nullable','in:open,pending,closed'],
        ]);

        $ticket = Ticket::create([
            'subject' => $data['subject'],
            'body' => $data['body'],
            'status' => $data['status'] ?? 'open',
        ]);

        return response()->json($ticket, 201);
    }

    public function show(Ticket $ticket): JsonResponse
    {
        return response()->json($ticket);
    }

    public function update(Request $request, Ticket $ticket): JsonResponse
    {
        $data = $request->validate([
            'status' => ['sometimes','in:open,pending,closed'],
            'category' => ['sometimes','nullable','string','max:120'],
            'note' => ['sometimes','nullable','string'],
        ]);

        $ticket->fill($data)->save();

        return response()->json($ticket);
    }

    public function classify(Ticket $ticket): JsonResponse
    {
        ClassifyTicket::dispatch($ticket);
        return response()->json(['queued' => true]);
    }
}
