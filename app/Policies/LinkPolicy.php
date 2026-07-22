<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksResourcePermissions;

class LinkPolicy
{
    use ChecksResourcePermissions;

    protected static string $permissionModel = 'link';
}
