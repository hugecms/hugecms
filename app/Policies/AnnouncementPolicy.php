<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksResourcePermissions;

class AnnouncementPolicy
{
    use ChecksResourcePermissions;

    protected static string $permissionModel = 'announcement';
}
