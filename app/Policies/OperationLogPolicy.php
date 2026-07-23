<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksResourcePermissions;

class OperationLogPolicy
{
    use ChecksResourcePermissions;

    protected static string $permissionModel = 'operation_log';
}
