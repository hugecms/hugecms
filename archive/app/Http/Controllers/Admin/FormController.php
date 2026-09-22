<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * 表单管理（form_templates + form_submissions）。
 */
class FormController extends Controller
{
    public function index(): View
    {
        return view('admin.forms.index');
    }

    public function create(): View
    {
        return view('admin.forms.form', ['mode' => 'create', 'id' => null]);
    }

    public function edit(int $id): View
    {
        return view('admin.forms.form', ['mode' => 'edit', 'id' => $id]);
    }

    /**
     * 表单提交数据查看。
     */
    public function submissions(int $id): View
    {
        return view('admin.forms.submissions', ['id' => $id]);
    }
}
