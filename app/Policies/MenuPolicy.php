<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksResourcePermissions;

class MenuPolicy
{
    use ChecksResourcePermissions;

    protected static string $permissionModel = 'menu';
}
