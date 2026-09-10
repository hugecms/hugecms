<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * 媒体库（attachments，多存储策略）。
 */
class AttachmentController extends Controller
{
    public function index(): View
    {
        return view('admin.attachments.index');
    }
}
