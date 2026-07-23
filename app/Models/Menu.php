<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'description'])]
class Menu extends Model
{
    use HasFactory;

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }

    /**
     * Render the active menu items as a nested tree.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function render(string $slug): array
    {
        $menu = self::where('slug', $slug)->first();

        if (! $menu) {
            return [];
        }

        $items = $menu->items()
            ->where('is_active', true)
            ->defaultOrder()
            ->with('linkable')
            ->get();

        return $items->toTree()
            ->map(fn (MenuItem $item) => $item->toRenderArray())
            ->all();
    }
}
