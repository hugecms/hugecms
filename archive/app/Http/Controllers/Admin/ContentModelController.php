<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * 内容模型管理（content_models + model_fields，字段在编辑页内维护）。
 */
class ContentModelController extends Controller
{
    public function index(): View
    {
        return view('admin.content-models.index');
    }

    public function create(): View
    {
        return view('admin.content-models.form', ['mode' => 'create', 'id' => null]);
    }

    public function edit(int $id): View
    {
        return view('admin.content-models.form', ['mode' => 'edit', 'id' => $id]);
    }
}
