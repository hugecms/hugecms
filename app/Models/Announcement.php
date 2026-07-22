<?php

namespace App\Models;

use App\Enums\PublishStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'content',
    'type',
    'status',
    'start_at',
    'end_at',
])]
class Announcement extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => PublishStatus::class,
            'start_at' => 'datetime',
            'end_at' => 'datetime',
        ];
    }
}
