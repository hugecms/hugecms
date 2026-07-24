@extends('layouts.app')

@section('title', $page->seo_title ?: $page->title)
@section('description', $page->seo_description ?: \App\Support\SiteSetting::get('site_description', ''))
@section('keywords', $page->seo_keywords ?: \App\Support\SiteSetting::get('site_keywords', ''))

@section('content')
<div class="container-narrow page-single">

    {{-- Header --}}
    <header class="page-single-header">
        <h1 class="page-title">
            {{ $page->title }}
        </h1>
        <p class="page-updated">
            更新于 {{ $page->updated_at ? $page->updated_at->format('Y-m-d') : $page->created_at->format('Y-m-d') }}
        </p>
    </header>

    {{-- Page Blocks or Rich Text --}}
    @if ($page->blocks && is_array($page->blocks) && count($page->blocks) > 0)
        <div class="page-blocks">
            @foreach ($page->blocks as $block)
                @includeIf('blocks.' . ($block['type'] ?? 'rich_text'), ['data' => $block['data'] ?? []])
            @endforeach
        </div>
    @else
        <article class="prose">
            {!! $page->content !!}
        </article>
    @endif
</div>
@endsection
