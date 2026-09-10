@extends('site.layouts.site')

@section('title', ($seo?->title ?: $content->title) . ' - ' . ($siteName ?? ''))

@section('meta')
    @if ($seo)
        @if ($seo->keywords)<meta name="keywords" content="{{ $seo->keywords }}">@endif
        @if ($seo->description)<meta name="description" content="{{ $seo->description }}">@endif
        @if ($seo->canonical_url)<link rel="canonical" href="{{ $seo->canonical_url }}">@endif
        @if ($seo->robots && $seo->robots !== 'index,follow')<meta name="robots" content="{{ $seo->robots }}">@endif
    @endif
@endsection

@section('content')
<article class="panel bg-white shadow-md">
    <div class="panel-body">
        <h1 style="margin:0 0 12px;font-size:26px">{{ $content->title }}</h1>
        <p class="text-gray-500 text-sm" style="margin:0 0 24px">
            <span>{{ optional($content->published_at)->format('Y-m-d H:i') ?? '' }}</span>
            <span style="margin-left:12px">{{ $content->views }} 次浏览</span>
            <span style="margin-left:12px">{{ $content->comment_count }} 条评论</span>
        </p>

        @if ($locked)
            <div class="alert bg-warning" style="padding:16px">
                该内容受访问保护（密码保护 / 仅登录可见），暂未开放直接浏览。
            </div>
        @else
            {{-- 摘要（field_1） --}}
            @if (!empty($data['field_1']))
                <blockquote class="text-gray-500" style="border-left:3px solid #d1d5db;margin:0 0 20px;padding:8px 16px;background:#f9fafb">
                    {{ $data['field_1'] }}
                </blockquote>
            @endif

            {{-- 正文（field_2，富文本 HTML）。
                XSS 净化属插件钩子职责（开发约定第五节），入库前应已处理；此处不做二次过滤 --}}
            <div class="article-content" style="line-height:1.8">
                {!! $data['field_2'] ?? '<p class="text-gray-500">（暂无正文）</p>' !!}
            </div>
        @endif
    </div>
</article>

<div id="comments" class="panel bg-white shadow-md" style="margin-top:24px">
    <div class="panel-heading"><strong>评论（{{ $comments->count() }}）</strong></div>
    <div class="panel-body">
        @if (session('comment_status'))
            <div class="alert bg-success text-white">{{ session('comment_status') }}</div>
        @endif

        @forelse ($comments->where('parent_id', 0) as $comment)
            @php $replies = $comments->where('parent_id', $comment->id); @endphp
            <div class="comment-item">
                <div class="flex items-center justify-between">
                    <strong>{{ $comment->author_name ?: '游客' }}</strong>
                    <span class="text-gray-500 text-sm">{{ $comment->created_at?->format('Y-m-d H:i') }}</span>
                </div>
                <p style="margin:8px 0 4px">{{ $comment->content }}</p>
                <button class="btn btn-link text-sm" style="padding:0" onclick="replyTo({{ $comment->id }}, '{{ addslashes($comment->author_name) }}')">回复</button>

                @if ($replies->isNotEmpty())
                    <div style="margin-top:10px;padding-left:16px;border-left:2px solid #f3f4f6">
                        @foreach ($replies as $reply)
                            <div style="padding:6px 0">
                                <span class="text-gray-500 text-sm">{{ $reply->author_name ?: '游客' }} · {{ $reply->created_at?->format('m-d H:i') }}</span>
                                <p style="margin:4px 0 0">{{ $reply->content }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <p class="text-gray-500">还没有评论，来抢沙发。</p>
        @endforelse

        {{-- 评论表单（POST /comment；先审后发由 options.comment_config 决定） --}}
        <form method="POST" action="{{ route('site.comment') }}" style="margin-top:24px">
            @csrf
            <input type="hidden" name="content_id" value="{{ $content->id }}">
            <input type="hidden" name="parent_id" id="parent-id" value="0">
            <p id="reply-hint" class="text-gray-500 text-sm" style="display:none">
                正在回复 <span id="reply-target"></span>
                <button type="button" class="btn btn-link text-sm" style="padding:0" onclick="cancelReply()">取消</button>
            </p>
            @if (!auth()->check())
                <div class="flex flex-wrap gap-2" style="margin-bottom:10px">
                    <input type="text" class="form-control" name="author_name" placeholder="昵称" style="max-width:200px" required>
                    <input type="email" class="form-control" name="author_email" placeholder="邮箱（不公开）" style="max-width:240px" required>
                </div>
            @endif
            <div class="form-group">
                <textarea name="content" class="form-control" rows="4" placeholder="说点什么…" required style="resize:vertical"></textarea>
            </div>
            @if ($errors->any())
                <div class="alert bg-danger text-white">{{ $errors->first() }}</div>
            @endif
            <button type="submit" class="btn bg-primary-500 text-white">发表评论</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function replyTo(id, name) {
        document.getElementById('parent-id').value = id;
        const hint = document.getElementById('reply-hint');
        hint.style.display = '';
        document.getElementById('reply-target').textContent = '@' + name;
        document.querySelector('textarea[name=content]').focus();
    }

    function cancelReply() {
        document.getElementById('parent-id').value = 0;
        document.getElementById('reply-hint').style.display = 'none';
    }
</script>
@endpush
