<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksResourcePermissions;

class MenuItemPolicy
{
    use ChecksResourcePermissions;

    protected static string $permissionModel = 'menu_item';
}
