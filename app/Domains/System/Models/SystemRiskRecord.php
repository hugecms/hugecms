<?php

declare(strict_types=1);

namespace App\Domains\System\Models;

use Illuminate\Database\Eloquent\Model;

class SystemRiskRecord extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_risk_records';

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
        'user_id',
        'rule_id',
        'object_type',
        'object_no',
        'action',
        'appeal_status',
        'appeal_note',
        'handle_note',
        'handled_at',
        'remark',
    ];
}
