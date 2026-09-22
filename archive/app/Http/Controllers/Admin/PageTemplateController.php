<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * 页面模板管理（page_templates）。
 */
class PageTemplateController extends Controller
{
    public function index(): View
    {
        return view('admin.page-templates.index');
    }

    public function create(): View
    {
        return view('admin.page-templates.form', ['mode' => 'create', 'id' => null]);
    }

    public function edit(int $id): View
    {
        return view('admin.page-templates.form', ['mode' => 'edit', 'id' => $id]);
    }
}
