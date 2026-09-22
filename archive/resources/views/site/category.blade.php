@extends('site.layouts.site')

@section('title', ($term->name ?? '分类') . ' - ' . ($taxonomy->name ?? ''))

@section('content')
<div class="panel bg-white shadow-md">
    <div class="panel-heading"><strong>{{ $taxonomy->name ?? '分类' }}：{{ $term->name }}</strong></div>
    <div class="panel-body">
        @forelse ($contents as $content)
            <article class="article-item">
                <h2 style="margin:0 0 8px;font-size:18px">
                    <a href="{{ route('site.show', ['slug' => $content->slug]) }}" style="color:#111827">
                        @if ($content->is_top)<span class="label bg-primary-500 text-white" style="margin-right:6px">置顶</span>@endif
                        {{ $content->title }}
                    </a>
                </h2>
                @if (!empty($summaries[$content->id]))
                    <p class="text-gray-500" style="margin:0">{{ \Illuminate\Support\Str::limit(strip_tags($summaries[$content->id]), 120) }}</p>
                @endif
            </article>
        @empty
            <p class="text-gray-500">该分类下暂无内容。</p>
        @endforelse

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
