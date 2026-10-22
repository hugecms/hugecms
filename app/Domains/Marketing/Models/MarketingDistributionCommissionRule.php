<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingDistributionCommissionRule extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'marketing_distribution_commission_rules';

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
        'scope_type',
        'scope_id',
        'level1_rate',
        'level2_rate',
        'status',
    ];
}
