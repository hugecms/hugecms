<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * 角色管理（roles + role_permissions + user_roles，权限分配在编辑页内维护）。
 */
class RoleController extends Controller
{
    public function index(): View
    {
        return view('admin.roles.index');
    }

    public function create(): View
    {
        return view('admin.roles.form', ['mode' => 'create', 'id' => null]);
    }

    public function edit(int $id): View
    {
        return view('admin.roles.form', ['mode' => 'edit', 'id' => $id]);
    }
}
