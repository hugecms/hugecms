<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ads';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'position_id',
        'title',
        'ad_type',
        'cover_image',
        'content',
        'link_url',
        'link_target',
        'sort',
        'start_time',
        'end_time',
        'display_limit',
        'click_limit',
        'display_count',
        'click_count',
        'status',
    ];
}
