<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelField extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'model_fields';

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
        'model_id',
        'field_name',
        'column_name',
        'field_label',
        'field_type',
        'column_type',
        'default_value',
        'is_required',
        'is_unique',
        'validation_rules',
        'extra_config',
        'sort_order',
    ];
}
