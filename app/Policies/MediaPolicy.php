<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksResourcePermissions;

class MediaPolicy
{
    use ChecksResourcePermissions;

    protected static string $permissionModel = 'media';
}
