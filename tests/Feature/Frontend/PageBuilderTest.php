<?php

namespace Tests\Feature\Frontend;

use App\Enums\ContentStatus;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageBuilderTest extends TestCase
{
    use RefreshDatabase;

    public function test_page_renders_blocks(): void
    {
        $page = Page::factory()->create([
            'status' => ContentStatus::Published,
            'content' => null,
            'blocks' => [
                [
                    'type' => 'hero',
                    'data' => [
                        'title' => '欢迎',
                        'subtitle' => '副标题',
                        'background_color' => 'white',
                        'padding_top' => 'md',
                        'padding_bottom' => 'md',
                        'container' => true,
                    ],
                ],
                [
                    'type' => 'rich_text',
                    'data' => [
                        'content' => '<p>富文本内容</p>',
                        'background_color' => 'white',
                        'padding_top' => 'md',
                        'padding_bottom' => 'md',
                        'container' => true,
                    ],
                ],
            ],
        ]);

        $response = $this->get(route('page.show', $page->slug));

        $response->assertOk();
        $response->assertSee('欢迎');
        $response->assertSee('副标题');
        $response->assertSee('富文本内容');
    }

    public function test_page_falls_back_to_content_as_rich_text_block(): void
    {
        $page = Page::factory()->create([
            'status' => ContentStatus::Published,
            'content' => '<p>旧版纯富文本内容</p>',
            'blocks' => null,
        ]);

        $response = $this->get(route('page.show', $page->slug));

        $response->assertOk();
        $response->assertSee('旧版纯富文本内容');
    }

    public function test_empty_page_does_not_throw(): void
    {
        $page = Page::factory()->create([
            'status' => ContentStatus::Published,
            'content' => null,
            'blocks' => null,
        ]);

        $response = $this->get(route('page.show', $page->slug));

        $response->assertOk();
    }

    public function test_unknown_block_type_is_skipped(): void
    {
        $page = Page::factory()->create([
            'status' => ContentStatus::Published,
            'content' => null,
            'blocks' => [
                [
                    'type' => 'non_existent_block',
                    'data' => ['foo' => 'bar'],
                ],
                [
                    'type' => 'rich_text',
                    'data' => [
                        'content' => '<p>可见内容</p>',
                        'background_color' => 'white',
                        'padding_top' => 'md',
                        'padding_bottom' => 'md',
                        'container' => true,
                    ],
                ],
            ],
        ]);

        $response = $this->get(route('page.show', $page->slug));

        $response->assertOk();
        $response->assertSee('可见内容');
        $response->assertDontSee('non_existent_block');
    }
}
