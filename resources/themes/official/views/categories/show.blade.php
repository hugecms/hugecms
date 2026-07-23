@extends('layouts.app')

@section('title', $category->seo_title ?: $category->name . ' - 分类 - ' . \App\Support\SiteSetting::get('site_name', config('app.name')))
@section('description', $category->seo_description ?: $category->description)
@section('keywords', $category->seo_keywords)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <div class="border-b border-[#f0f0f0] dark:border-[#303030] pb-6 space-y-1.5">
        <span class="antd-tag">分类归档</span>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $category->name }}</h1>
        @if ($category->description)
            <p class="text-xs text-slate-500">{{ $category->description }}</p>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse ($articles as $article)
            @php
                $coverUrl = $article->getFirstMediaUrl('cover', 'medium') ?: ($article->cover_image ? asset('storage/'.$article->cover_image) : null);
            @endphp
            <article class="antd-card p-5 flex flex-col justify-between space-y-3">
                <div class="space-y-2">
                    @if ($coverUrl)
                        <div class="overflow-hidden rounded aspect-[16/10] bg-slate-100 dark:bg-slate-900">
                            <img src="{{ $coverUrl }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                        </div>
                    @endif
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white hover:text-[#1677FF] transition-colors line-clamp-2">
                        <a href="{{ route('article.show', $article->slug) }}">{{ $article->title }}</a>
                    </h3>
                </div>
                <a href="{{ route('article.show', $article->slug) }}" class="text-xs font-semibold text-[#1677FF]">阅读全文 →</a>
            </article>
        @empty
            <div class="col-span-full py-16 text-center text-xs text-slate-400">该分类下暂无已发布的文章</div>
        @endforelse
    </div>
</div>
@endsection
