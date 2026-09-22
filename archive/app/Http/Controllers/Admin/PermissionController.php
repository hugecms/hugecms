<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * 权限管理（permissions 树形，种子数据来自 CmsSeeder）。
 */
class PermissionController extends Controller
{
    public function index(): View
    {
        return view('admin.permissions.index');
    }
}
