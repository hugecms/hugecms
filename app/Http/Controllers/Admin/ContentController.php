<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * 内容管理（contents + data_{alias}）。数据经 /api/admin/content* 接口获取。
 */
class ContentController extends Controller
{
    public function index(): View
    {
        return view('admin.content.index');
    }

    public function create(): View
    {
        return view('admin.content.form', ['mode' => 'create', 'id' => null]);
    }

    public function edit(int $id): View
    {
        return view('admin.content.form', ['mode' => 'edit', 'id' => $id]);
    }
}
