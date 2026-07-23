<?php

namespace Tests\Feature\Frontend;

use App\Models\Setting;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThemeRenderingTest extends TestCase
{
    use RefreshDatabase;

    private string $themeId = 'test-blog';

    private Filesystem $files;

    protected function setUp(): void
    {
        parent::setUp();

        $this->files = new Filesystem;
        $this->createTestTheme();
    }

    protected function tearDown(): void
    {
        $this->files->deleteDirectory(resource_path('themes/'.$this->themeId));
        Setting::where('group', 'site')->where('key', 'active_theme')->delete();

        parent::tearDown();
    }

    public function test_active_theme_view_is_rendered(): void
    {
        Setting::create(['group' => 'site', 'key' => 'active_theme', 'value' => $this->themeId]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('TEST-BLOG-HOME');
        $response->assertDontSee('暂无已发布的文章');
    }

    public function test_missing_view_falls_back_to_default_theme(): void
    {
        Setting::create(['group' => 'site', 'key' => 'active_theme', 'value' => $this->themeId]);

        $this->files->delete(resource_path('themes/'.$this->themeId.'/views/home.blade.php'));

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('暂无已发布的文章');
    }

    private function createTestTheme(): void
    {
        $base = resource_path('themes/'.$this->themeId);
        $this->files->makeDirectory($base.'/views', 0755, true);
        $this->files->put($base.'/theme.json', json_encode([
            'name' => $this->themeId,
            'label' => '测试博客主题',
            'version' => '1.0.0',
            'author' => 'Test',
            'preview' => null,
        ]));
        $this->files->put($base.'/views/home.blade.php', 'TEST-BLOG-HOME');
    }
}
