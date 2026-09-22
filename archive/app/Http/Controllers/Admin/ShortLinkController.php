<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * 短链接管理（short_links + short_link_clicks；插件化保留模块）。
 */
class ShortLinkController extends Controller
{
    public function index(): View
    {
        return view('admin.short-links.index');
    }

    public function create(): View
    {
        return view('admin.short-links.form', ['mode' => 'create', 'id' => null]);
    }

    public function edit(int $id): View
    {
        return view('admin.short-links.form', ['mode' => 'edit', 'id' => $id]);
    }

    /**
     * 点击明细。
     */
    public function clicks(int $id): View
    {
        return view('admin.short-links.clicks', ['id' => $id]);
    }
}
