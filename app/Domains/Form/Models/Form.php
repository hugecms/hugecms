<?php

declare(strict_types=1);

namespace App\Domains\Form\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'forms';

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
        'description',
        'success_message',
        'start_time',
        'end_time',
        'need_login',
        'max_submits',
        'status',
    ];
}
