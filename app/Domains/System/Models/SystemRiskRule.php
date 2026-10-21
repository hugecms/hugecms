<?php

declare(strict_types=1);

namespace App\Domains\System\Models;

use Illuminate\Database\Eloquent\Model;

class SystemRiskRule extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_risk_rules';

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
        'rule_type',
        'condition_expr',
        'action',
        'status',
    ];
}
