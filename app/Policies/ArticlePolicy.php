<?php

namespace App\Policies;

use App\Policies\Concerns\ChecksResourcePermissions;

class ArticlePolicy
{
    use ChecksResourcePermissions;

    protected static string $permissionModel = 'article';
}
