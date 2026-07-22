<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksResourcePermissions;

class MediaCategoryPolicy
{
    use ChecksResourcePermissions;

    protected static string $permissionModel = 'media_category';
}
