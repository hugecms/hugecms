<?php

declare(strict_types=1);

namespace App\Domains\Site\Models;

use Illuminate\Database\Eloquent\Model;

class SiteBanner extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'site_banners';

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
        'position',
        'title',
        'image',
        'link',
        'description',
        'sort',
        'status',
        'start_time',
        'end_time',
        'create_time',
        'update_time',
    ];
}
