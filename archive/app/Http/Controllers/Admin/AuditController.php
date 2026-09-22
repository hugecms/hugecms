<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * 审计日志（audit_logs，只读查询）。
 */
class AuditController extends Controller
{
    public function index(): View
    {
        return view('admin.audit.index');
    }
}
