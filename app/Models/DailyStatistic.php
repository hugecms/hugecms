<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['date', 'page_views', 'unique_visitors', 'new_users'])]
class DailyStatistic extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'page_views' => 'integer',
            'unique_visitors' => 'integer',
            'new_users' => 'integer',
        ];
    }
}
