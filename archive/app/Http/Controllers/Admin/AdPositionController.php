<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * 广告位管理（ad_positions，模板按 code 调用；插件化保留模块）。
 */
class AdPositionController extends Controller
{
    public function index(): View
    {
        return view('admin.ad-positions.index');
    }

    public function create(): View
    {
        return view('admin.ad-positions.form', ['mode' => 'create', 'id' => null]);
    }

    public function edit(int $id): View
    {
        return view('admin.ad-positions.form', ['mode' => 'edit', 'id' => $id]);
    }
}
