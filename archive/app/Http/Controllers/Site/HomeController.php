<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Content;
use App\Models\ContentModel;
use App\Models\SeoMeta;
use App\Models\Term;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 前台站点：首页 / 内容详情 / 分类页。
 * 服务端渲染，直查数据库；前台可见条件见开发约定第二节：
 * status=published AND audit_status=approved（列表另加 visibility=public）。
 */
class HomeController extends Controller
{
    private const PAGE_SIZE = 10;

    public function index(Request $request): View
    {
        $contents = $this->publishedQuery()
            ->orderByDesc('is_top')
            ->orderByDesc('published_at')
            ->paginate(self::PAGE_SIZE)
            ->withQueryString();

        return view('site.index', [
            'contents' => $contents,
            'summaries' => $this->summaries($contents->getCollection()->pluck('id')->all()),
        ]);
    }

    public function show(string $slug): View
    {
        $content = Content::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('audit_status', 'approved')
            ->first();

        if (!$content) {
            throw (new ModelNotFoundException())->setModel(Content::class, [$slug]);
        }

        // 可见性：私密仅登录可见；密码保护暂提示（口令校验表单待实现）
        $locked = false;
        if ($content->visibility === 'private' && !auth()->check()) {
            $locked = true;
        } elseif ($content->visibility === 'password' && !request()->session()->get('content_unlocked.' . $content->id)) {
            $locked = true; // TODO: 密码口令校验通过后写 session 解锁
        }

        // 模型数据行（摘要/正文等动态字段）
        $model = ContentModel::query()->find($content->model_id);
        $data = [];
        if ($model?->table_name && DB::getSchemaBuilder()->hasTable($model->table_name)) {
            $row = DB::table($model->table_name)->where('content_id', $content->id)->first();
            $data = $row ? (array) $row : [];
        }

        $seo = SeoMeta::query()
            ->where('target_type', 'content')
            ->where('target_id', $content->id)
            ->first();

        // 已通过审核的顶级评论（楼中楼按 parent_id 归组渲染）
        $comments = Comment::query()
            ->where('content_id', $content->id)
            ->where('status', 'approved')
            ->orderBy('parent_id')
            ->orderBy('created_at')
            ->take(100)
            ->get();

        return view('site.detail', [
            'content' => $content,
            'data' => $data,
            'seo' => $seo,
            'locked' => $locked,
            'comments' => $comments,
        ]);
    }

    public function category(string $taxonomyAlias, string $termSlug): View
    {
        $term = Term::query()
            ->where('slug', $termSlug)
            ->first();

        $taxonomy = $term ? DB::table('taxonomies')->where('id', $term->taxonomy_id)->first() : null;
        if (!$term || !$taxonomy || $taxonomy->alias !== $taxonomyAlias) {
            abort(404);
        }

        $contentIds = DB::table('term_relationships')->where('term_id', $term->id)->pluck('content_id');

        $contents = $this->publishedQuery()
            ->whereIn('id', $contentIds)
            ->orderByDesc('is_top')
            ->orderByDesc('published_at')
            ->paginate(self::PAGE_SIZE);

        return view('site.category', [
            'term' => $term,
            'taxonomy' => $taxonomy,
            'contents' => $contents,
            'summaries' => $this->summaries($contents->getCollection()->pluck('id')->all()),
        ]);
    }

    /**
     * 前台可见内容的基础查询。
     */
    private function publishedQuery()
    {
        return Content::query()
            ->where('status', 'published')
            ->where('audit_status', 'approved')
            ->where('visibility', 'public');
    }

    /**
     * 批量取摘要（field_1）与正文片段，避免 N+1。
     *
     * @param  array<int, int>  $contentIds
     * @return array<int, string>
     */
    private function summaries(array $contentIds): array
    {
        if (empty($contentIds)) {
            return [];
        }

        $rows = DB::table('data_article')
            ->whereIn('content_id', $contentIds)
            ->select('content_id', 'field_1')
            ->get();

        $map = [];
        foreach ($rows as $row) {
            $map[(int) $row->content_id] = (string) ($row->field_1 ?? '');
        }

        return $map;
    }
}
