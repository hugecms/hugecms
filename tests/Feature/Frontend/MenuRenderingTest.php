<?php

namespace Tests\Feature\Frontend;

use App\Enums\ContentStatus;
use App\Enums\MenuItemType;
use App\Models\Article;
use App\Models\Category;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuRenderingTest extends TestCase
{
    use RefreshDatabase;

    public function test_main_menu_renders_in_layout(): void
    {
        $menu = Menu::factory()->main()->create();
        $category = Category::factory()->create();
        MenuItem::factory()
            ->forMenu($menu)
            ->category($category)
            ->create(['title' => '产品分类']);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('产品分类');
        $response->assertSee(route('category.show', $category->slug), false);
    }

    public function test_menu_render_returns_nested_tree(): void
    {
        $menu = Menu::factory()->main()->create();
        $parent = MenuItem::factory()->forMenu($menu)->create(['title' => 'Parent']);
        MenuItem::factory()
            ->forMenu($menu)
            ->create([
                'title' => 'Child',
                'parent_id' => $parent->id,
            ]);

        $tree = Menu::render('main');

        $this->assertCount(1, $tree);
        $this->assertSame('Parent', $tree[0]['title']);
        $this->assertCount(1, $tree[0]['children']);
        $this->assertSame('Child', $tree[0]['children'][0]['title']);
    }

    public function test_inactive_menu_items_are_not_rendered(): void
    {
        $menu = Menu::factory()->main()->create();
        MenuItem::factory()->forMenu($menu)->create(['title' => 'Active']);
        MenuItem::factory()
            ->forMenu($menu)
            ->inactive()
            ->create(['title' => 'Inactive']);

        $rendered = Menu::render('main');
        $titles = array_column($rendered, 'title');

        $this->assertContains('Active', $titles);
        $this->assertNotContains('Inactive', $titles);
    }

    public function test_menu_item_resolves_custom_url(): void
    {
        $item = MenuItem::factory()->create([
            'type' => MenuItemType::Custom,
            'url' => 'https://example.com',
        ]);

        $this->assertSame('https://example.com', $item->resolveUrl());
    }

    public function test_menu_item_resolves_category_url(): void
    {
        $category = Category::factory()->create();
        $item = MenuItem::factory()->category($category)->create();

        $this->assertSame(route('category.show', $category->slug), $item->resolveUrl());
    }

    public function test_menu_item_resolves_page_url(): void
    {
        $page = Page::factory()->create();
        $item = MenuItem::factory()->page($page)->create();

        $this->assertSame(route('page.show', $page->slug), $item->resolveUrl());
    }

    public function test_menu_item_resolves_article_url(): void
    {
        $article = Article::factory()->create();
        $item = MenuItem::factory()->article($article)->create();

        $this->assertSame(route('article.show', $article->slug), $item->resolveUrl());
    }

    public function test_current_menu_item_is_marked(): void
    {
        $menu = Menu::factory()->main()->create();
        $page = Page::factory()->create(['status' => ContentStatus::Published]);
        $item = MenuItem::factory()->page($page)->forMenu($menu)->create();

        $rendered = Menu::render('main');

        $this->assertSame($item->title, $rendered[0]['title']);
        $this->assertFalse($rendered[0]['is_current']);

        $this->get(route('page.show', $page->slug));

        $rendered = Menu::render('main');
        $this->assertTrue($rendered[0]['is_current']);
    }
}
