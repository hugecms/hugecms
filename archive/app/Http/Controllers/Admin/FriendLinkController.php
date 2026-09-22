<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * 友情链接管理（friend_links，含审核流；插件化保留模块）。
 */
class FriendLinkController extends Controller
{
    public function index(): View
    {
        return view('admin.friend-links.index');
    }

    public function create(): View
    {
        return view('admin.friend-links.form', ['mode' => 'create', 'id' => null]);
    }

    public function edit(int $id): View
    {
        return view('admin.friend-links.form', ['mode' => 'edit', 'id' => $id]);
    }
}
