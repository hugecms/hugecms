<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Content;
use App\Models\ContentModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 前台评论提交（表单直 POST，服务端渲染配套）。
 * 审核开关取 options.comment_config（与模型级 is_commentable 取与）。
 */
class CommentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'content_id' => ['required', 'integer', 'exists:contents,id'],
            'parent_id' => ['nullable', 'integer'],
            'author_name' => ['nullable', 'string', 'max:50'],
            'author_email' => ['nullable', 'email', 'max:100'],
            'author_url' => ['nullable', 'url', 'max:255'],
            'content' => ['required', 'string', 'max:2000'],
        ], [], ['content' => '评论内容']);

        $content = Content::query()->findOrFail((int) $data['content_id']);

        // 评论开关：全局配置 与 模型级 is_commentable 取与
        $config = $this->commentConfig();
        $model = ContentModel::query()->find($content->model_id);
        $commentable = ($config['guest_allowed'] ?? true) || auth()->check();
        if (!($config['enabled'] ?? true) || !($model?->is_commentable ?? true) || !$commentable) {
            return back()->withErrors(['content' => '当前内容未开放评论'])->withInput();
        }

        $status = ($config['require_moderation'] ?? true) ? 'pending' : 'approved';

        $replyTo = null;
        $parentId = (int) ($data['parent_id'] ?? 0);
        if ($parentId > 0) {
            $parent = Comment::query()->find($parentId);
            if ($parent && $parent->content_id === $content->id) {
                $replyTo = $parent->user_id;
            } else {
                $parentId = 0;
            }
        }

        Comment::query()->create([
            'content_id' => $content->id,
            'user_id' => auth()->id(),
            'parent_id' => $parentId,
            'reply_to_user_id' => $replyTo,
            'author_name' => $data['author_name'] ?? auth()->user()?->name ?? '游客',
            'author_email' => $data['author_email'] ?? auth()->user()?->email ?? '',
            'author_url' => $data['author_url'] ?? '',
            'content' => $data['content'],
            'ip' => $request->ip() ?? '',
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
            'status' => $status,
        ]);

        if ($status === 'approved') {
            $content->increment('comment_count'); // 冗余计数（事务化见开发约定第六节）
        }

        $message = $status === 'pending' ? '评论已提交，审核通过后显示。' : '评论发表成功。';

        return redirect()->to(url()->previous() . '#comments')->with('comment_status', $message);
    }

    /**
     * @return array<string, mixed>
     */
    private function commentConfig(): array
    {
        $raw = DB::table('options')->where('option_key', 'comment_config')->value('option_value');

        $decoded = \is_string($raw) ? \json_decode($raw, true) : null;

        return \is_array($decoded) ? $decoded : [];
    }
}
