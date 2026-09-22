<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * 内容推送（content_push_queue：公众号/RSS/站长平台；插件化保留模块）。
 */
class ContentPushController extends Controller
{
    public function index(): View
    {
        return view('admin.push.index');
    }
}
