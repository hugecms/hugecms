<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * 重定向管理（redirects，slug 改版/迁移的 SEO 保护）。
 */
class RedirectController extends Controller
{
    public function index(): View
    {
        return view('admin.redirects.index');
    }

    public function create(): View
    {
        return view('admin.redirects.form', ['mode' => 'create', 'id' => null]);
    }

    public function edit(int $id): View
    {
        return view('admin.redirects.form', ['mode' => 'edit', 'id' => $id]);
    }
}
