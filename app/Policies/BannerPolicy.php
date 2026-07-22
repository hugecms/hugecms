<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksResourcePermissions;

class BannerPolicy
{
    use ChecksResourcePermissions;

    protected static string $permissionModel = 'banner';
}
