@extends('layouts.app')

@section('title', $page->seo_title ?: $page->title)
@section('description', $page->seo_description ?: \App\Support\SiteSetting::get('site_description', ''))
@section('keywords', $page->seo_keywords ?: \App\Support\SiteSetting::get('site_keywords', ''))

@section('content')
<div class="container-narrow article-page">
    <header class="page-header">
        <h1 class="page-title">
            {{ $page->title }}
        </h1>
    </header>

    @if ($page->blocks && is_array($page->blocks) && count($page->blocks) > 0)
        <div class="stack-8">
            @foreach ($page->blocks as $block)
                @includeIf('blocks.' . ($block['type'] ?? 'rich_text'), ['data' => $block['data'] ?? []])
            @endforeach
        </div>
    @else
        <article class="prose article-body">
            {!! $page->content !!}
        </article>
    @endif
</div>
@endsection
