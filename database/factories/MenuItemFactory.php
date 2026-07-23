<?php

namespace Database\Factories;

use App\Enums\MenuItemTarget;
use App\Enums\MenuItemType;
use App\Models\Article;
use App\Models\Category;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory\<MenuItem\>
 */
class MenuItemFactory extends Factory
{
    protected $model = MenuItem::class;

    public function definition(): array
    {
        return [
            'menu_id' => Menu::factory(),
            'title' => $this->faker->words(2, true),
            'type' => MenuItemType::Custom,
            'target' => MenuItemTarget::Self,
            'url' => $this->faker->url(),
            'order' => 0,
            'is_active' => true,
        ];
    }

    public function forMenu(Menu $menu): static
    {
        return $this->state(fn (array $attributes) => [
            'menu_id' => $menu->id,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function category(?Category $category = null): static
    {
        return $this->state(function (array $attributes) use ($category) {
            $category ??= Category::factory()->create();

            return [
                'type' => MenuItemType::Category,
                'url' => null,
                'linkable_type' => Category::class,
                'linkable_id' => $category->id,
            ];
        });
    }

    public function page(?Page $page = null): static
    {
        return $this->state(function (array $attributes) use ($page) {
            $page ??= Page::factory()->create();

            return [
                'type' => MenuItemType::Page,
                'url' => null,
                'linkable_type' => Page::class,
                'linkable_id' => $page->id,
            ];
        });
    }

    public function article(?Article $article = null): static
    {
        return $this->state(function (array $attributes) use ($article) {
            $article ??= Article::factory()->create();

            return [
                'type' => MenuItemType::Article,
                'url' => null,
                'linkable_type' => Article::class,
                'linkable_id' => $article->id,
            ];
        });
    }
}
