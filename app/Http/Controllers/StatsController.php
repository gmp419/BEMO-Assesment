<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    public function index(): JsonResponse
    {
        $perStatus = Ticket::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count','status');

        $perCategory = Ticket::selectRaw('COALESCE(category, "uncategorized") as category, COUNT(*) as count')
            ->groupBy('category')
            ->pluck('count','category');

        return response()->json([
            'perStatus' => $perStatus,
            'perCategory' => $perCategory,
        ]);
    }
}



