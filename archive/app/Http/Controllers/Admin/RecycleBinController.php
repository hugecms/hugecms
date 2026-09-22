<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * 回收站（recycle_bin，快照恢复约定见 docs/development-conventions.md 第一节）。
 */
class RecycleBinController extends Controller
{
    public function index(): View
    {
        return view('admin.recycle.index');
    }
}
