<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * 广告管理（ad_positions + ads，页内分 Tab 管理；插件化保留模块）。
 */
class AdController extends Controller
{
    public function index(): View
    {
        return view('admin.ads.index');
    }

    public function create(): View
    {
        return view('admin.ads.form', ['mode' => 'create', 'id' => null]);
    }

    public function edit(int $id): View
    {
        return view('admin.ads.form', ['mode' => 'edit', 'id' => $id]);
    }
}
