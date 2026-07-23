<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksResourcePermissions;

class DailyStatisticPolicy
{
    use ChecksResourcePermissions;

    protected static string $permissionModel = 'daily_statistic';
}
