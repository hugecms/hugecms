<?php

namespace Tests\Feature\Frontend;

use App\Models\Setting;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThemeModeToggleTest extends TestCase
{
    use RefreshDatabase;

    private string $themeId = 'test-toggle-theme';

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

    public function test_home_page_renders_theme_mode_toggle(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('id="theme-toggle"', false);
        $response->assertSee('aria-pressed', false);
        $response->assertSee('dark:hidden', false);
        $response->assertSee('hidden dark:block', false);
    }

    public function test_theme_mode_init_script_appears_before_vite_assets(): void
    {
        $response = $this->get(route('home'));
        $content = $response->getContent();

        $initScriptPosition = strpos($content, 'localStorage.getItem(\'theme\')');
        $vitePosition = strpos($content, '/build/assets/');

        $this->assertNotFalse($initScriptPosition, 'Theme mode init script not found.');
        $this->assertTrue(
            $vitePosition === false || $initScriptPosition < $vitePosition,
            'Theme mode init script must appear before Vite assets to prevent FOUC.'
        );
    }

    public function test_missing_view_falls_back_to_default_theme_with_toggle(): void
    {
        Setting::create(['group' => 'site', 'key' => 'active_theme', 'value' => $this->themeId]);

        $this->files->delete(resource_path('themes/'.$this->themeId.'/views/home.blade.php'));

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('id="theme-toggle"', false);
    }

    private function createTestTheme(): void
    {
        $base = resource_path('themes/'.$this->themeId);
        $this->files->makeDirectory($base.'/views', 0755, true);
        $this->files->put($base.'/theme.json', json_encode([
            'name' => $this->themeId,
            'label' => '测试切换主题',
            'version' => '1.0.0',
            'author' => 'Test',
            'preview' => null,
        ]));
        $this->files->put($base.'/views/home.blade.php', 'TEST-TOGGLE-HOME');
    }
}
