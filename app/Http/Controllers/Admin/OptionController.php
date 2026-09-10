<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * 系统设置（options：常规/评论/存储/邮件/固定链接 等配置组）。
 */
class OptionController extends Controller
{
    public function index(): View
    {
        return view('admin.options.index');
    }
}
