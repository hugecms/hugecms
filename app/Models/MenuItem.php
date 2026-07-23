<?php

namespace App\Models;

use App\Enums\MenuItemTarget;
use App\Enums\MenuItemType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Kalnoy\Nestedset\NodeTrait;

#[Fillable([
    'menu_id',
    'parent_id',
    'title',
    'url',
    'type',
    'target',
    'order',
    'is_active',
    'linkable_type',
    'linkable_id',
    '_lft',
    '_rgt',
])]
class MenuItem extends Model
{
    use HasFactory, NodeTrait;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'type' => MenuItemType::class,
            'target' => MenuItemTarget::class,
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (MenuItem $item): void {
            if ($item->type === MenuItemType::Custom) {
                $item->linkable_type = null;
                $item->linkable_id = null;

                return;
            }

            $item->linkable_type = match ($item->type) {
                MenuItemType::Category => Category::class,
                MenuItemType::Page => Page::class,
                MenuItemType::Article => Article::class,
                default => null,
            };
        });
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function linkable(): MorphTo
    {
        return $this->morphTo();
    }

    public function resolveUrl(): string
    {
        if ($this->type === MenuItemType::Custom) {
            return $this->url ?: '#';
        }

        if (! $this->linkable) {
            return '#';
        }

        return match ($this->type) {
            MenuItemType::Category => route('category.show', $this->linkable->slug),
            MenuItemType::Page => route('page.show', $this->linkable->slug),
            MenuItemType::Article => route('article.show', $this->linkable->slug),
            default => '#',
        };
    }

    public function isCurrent(): bool
    {
        $url = $this->resolveUrl();

        return request()->url() === $url || request()->fullUrlIs($url);
    }

    /**
     * @return array<string, mixed>
     */
    public function toRenderArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'url' => $this->resolveUrl(),
            'target' => $this->target->value,
            'is_current' => $this->isCurrent(),
            'children' => $this->children
                ? $this->children->map(fn (MenuItem $child) => $child->toRenderArray())->all()
                : [],
        ];
    }
}
