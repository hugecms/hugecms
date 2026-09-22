<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sites';

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
        'site_name',
        'site_code',
        'domain',
        'domains',
        'site_logo',
        'favicon',
        'timezone',
        'language',
        'template_id',
        'config',
        'status',
    ];
}
