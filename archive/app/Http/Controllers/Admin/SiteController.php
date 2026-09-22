<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * 站点管理（sites，默认单站点一条记录）。
 */
class SiteController extends Controller
{
    public function index(): View
    {
        return view('admin.sites.index');
    }

    public function edit(int $id): View
    {
        return view('admin.sites.form', ['mode' => 'edit', 'id' => $id]);
    }
}
