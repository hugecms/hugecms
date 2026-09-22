<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * 区块管理（blocks，可复用页面组件）。
 */
class BlockController extends Controller
{
    public function index(): View
    {
        return view('admin.blocks.index');
    }

    public function create(): View
    {
        return view('admin.blocks.form', ['mode' => 'create', 'id' => null]);
    }

    public function edit(int $id): View
    {
        return view('admin.blocks.form', ['mode' => 'edit', 'id' => $id]);
    }
}
