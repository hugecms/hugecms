<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksResourcePermissions;

class CommentPolicy
{
    use ChecksResourcePermissions;

    protected static string $permissionModel = 'comment';
}
