<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * 评论管理（comments，含待审核队列）。
 */
class CommentController extends Controller
{
    public function index(): View
    {
        return view('admin.comments.index');
    }
}
