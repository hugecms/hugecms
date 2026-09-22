<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * 分类与标签管理（taxonomies + terms）。
 */
class TaxonomyController extends Controller
{
    public function index(): View
    {
        return view('admin.taxonomies.index');
    }

    public function create(): View
    {
        return view('admin.taxonomies.form', ['mode' => 'create', 'id' => null]);
    }

    public function edit(int $id): View
    {
        return view('admin.taxonomies.form', ['mode' => 'edit', 'id' => $id]);
    }
}
