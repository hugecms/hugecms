<?php

declare(strict_types=1);

namespace App\Domains\Form\Models;

use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'form_fields';

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
        'form_id',
        'name',
        'field_name',
        'type',
        'options',
        'default_value',
        'is_required',
        'pattern',
        'sort',
        'status',
    ];
}
