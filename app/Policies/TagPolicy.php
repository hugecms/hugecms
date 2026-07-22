<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksResourcePermissions;

class TagPolicy
{
    use ChecksResourcePermissions;

    protected static string $permissionModel = 'tag';
}
