<?php

declare(strict_types=1);

namespace App\Domains\Site\Models;

use Illuminate\Database\Eloquent\Model;

class SiteLink extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'site_links';

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
        'name',
        'url',
        'logo',
        'description',
        'sort',
        'status',
    ];
}
