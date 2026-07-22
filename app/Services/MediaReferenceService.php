<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Category;
use App\Models\Page;
use Illuminate\Support\Collection as SupportCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaReferenceService
{
    /**
     * @return array{
     *     referenced: bool,
     *     references: SupportCollection,
     * }
     */
    public function check(Media $media): array
    {
        $references = $this->getReferences($media);

        return [
            'referenced' => $references->isNotEmpty(),
            'references' => $references,
        ];
    }

    public function isReferenced(Media $media): bool
    {
        return $this->check($media)['referenced'];
    }

    public function getReferences(Media $media): SupportCollection
    {
        $references = collect();

        $references = $references->merge($this->getModelReferences($media));
        $references = $references->merge($this->getContentReferences($media));

        return $references;
    }

    /**
     * @return SupportCollection<array{type: string, title: string, url: string|null}>
     */
    protected function getModelReferences(Media $media): SupportCollection
    {
        $owner = $media->model;

        if ($owner === null) {
            return collect();
        }

        if ($owner instanceof Article) {
            return collect([[
                'type' => '文章封面',
                'title' => $owner->title,
                'url' => route('filament.admin.resources.articles.edit', $owner),
            ]]);
        }

        if ($owner instanceof Page) {
            return collect([[
                'type' => '页面封面',
                'title' => $owner->title,
                'url' => route('filament.admin.resources.pages.edit', $owner),
            ]]);
        }

        if ($owner instanceof Category) {
            return collect([[
                'type' => '分类封面',
                'title' => $owner->name,
                'url' => route('filament.admin.resources.categories.edit', $owner),
            ]]);
        }

        return collect();
    }

    /**
     * @return SupportCollection<array{type: string, title: string, url: string|null}>
     */
    protected function getContentReferences(Media $media): SupportCollection
    {
        $references = collect();
        $fileName = $media->file_name;

        Article::withTrashed()
            ->where('content', 'like', "%{$fileName}%")
            ->get()
            ->each(function (Article $article) use ($references) {
                $references->push([
                    'type' => '文章正文',
                    'title' => $article->title,
                    'url' => route('filament.admin.resources.articles.edit', $article),
                ]);
            });

        Page::withTrashed()
            ->where('content', 'like', "%{$fileName}%")
            ->get()
            ->each(function (Page $page) use ($references) {
                $references->push([
                    'type' => '页面正文',
                    'title' => $page->title,
                    'url' => route('filament.admin.resources.pages.edit', $page),
                ]);
            });

        return $references;
    }
}
