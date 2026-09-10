<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * 导航菜单管理（nav_menus + nav_items，菜单项在编辑页内可视化维护）。
 */
class MenuController extends Controller
{
    public function index(): View
    {
        return view('admin.menus.index');
    }

    public function create(): View
    {
        return view('admin.menus.form', ['mode' => 'create', 'id' => null]);
    }

    public function edit(int $id): View
    {
        return view('admin.menus.form', ['mode' => 'edit', 'id' => $id]);
    }
}
