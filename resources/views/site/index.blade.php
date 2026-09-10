@extends('site.layouts.site')

@section('title', $siteName ?? '首页')

@section('content')
<div class="panel bg-white shadow-md">
    <div class="panel-heading"><strong>最新内容</strong></div>
    <div class="panel-body">
        @forelse ($contents as $content)
            <article class="article-item @if ($loop->first) @endif">
                <h2 style="margin:0 0 8px;font-size:20px">
                    <a href="{{ route('site.show', ['slug' => $content->slug]) }}" style="color:#111827">
                        @if ($content->is_top)<span class="label bg-primary-500 text-white" style="margin-right:6px">置顶</span>@endif
                        {{ $content->title }}
                    </a>
                </h2>
                @if (!empty($summaries[$content->id]))
                    <p class="text-gray-500" style="margin:0 0 8px">{{ \Illuminate\Support\Str::limit(strip_tags($summaries[$content->id]), 160) }}</p>
                @endif
                <p class="text-gray-500 text-sm" style="margin:0">
                    <span>{{ optional($content->published_at)->format('Y-m-d') ?? $content->created_at?->format('Y-m-d') }}</span>
                    <span style="margin-left:12px">{{ $content->views }} 次浏览</span>
                    <span style="margin-left:12px">{{ $content->comment_count }} 条评论</span>
                </p>
            </article>
        @empty
            <p class="text-gray-500">暂无内容，去后台发布第一篇吧。</p>
        @endforelse

        {{-- 简易分页 --}}
        @if ($contents->hasPages())
            <nav class="flex items-center gap-2" style="margin-top:20px">
                @if ($contents->onFirstPage())
                    <span class="btn btn-default">上一页</span>
                @else
                    <a class="btn btn-default" href="{{ $contents->previousPageUrl() }}">上一页</a>
                @endif
                <span class="text-gray-500 text-sm">第 {{ $contents->currentPage() }} 页 / 共 {{ $contents->lastPage() }} 页</span>
                @if ($contents->hasMorePages())
                    <a class="btn btn-default" href="{{ $contents->nextPageUrl() }}">下一页</a>
                @else
                    <span class="btn btn-default">下一页</span>
                @endif
            </nav>
        @endif
    </div>
</div>
@endsection
