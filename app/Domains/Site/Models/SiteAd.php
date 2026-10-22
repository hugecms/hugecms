<?php

declare(strict_types=1);

namespace App\Domains\Site\Models;

use Illuminate\Database\Eloquent\Model;

class SiteAd extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'site_ads';

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
        'position_code',
        'title',
        'image',
        'link',
        'description',
        'sort',
        'status',
        'start_time',
        'end_time',
    ];
}
