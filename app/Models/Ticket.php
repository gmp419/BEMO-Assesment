<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'subject', 'body', 'status', 'category', 'note', 'confidence', 'explanation',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $ticket) {
            if (empty($ticket->id)) {
                $ticket->id = (string) Str::ulid();
            }
        });
    }

    protected $casts = [
        'confidence' => 'float',
    ];
}