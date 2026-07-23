<?php

namespace Tests\Feature\Themes;

use App\Models\Setting;
use App\Models\User;
use App\Themes\Theme;
use App\Themes\ThemeRegistry;
use App\Themes\ThemeResolver;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ThemeResolverTest extends TestCase
{
    use RefreshDatabase;

    private string $themesPath;

    private Filesystem $files;

    protected function setUp(): void
    {
        parent::setUp();

        $this->themesPath = sys_get_temp_dir().'/hugecms-resolver-'.uniqid();
        $this->files = new Filesystem;
        $this->files->makeDirectory($this->themesPath, 0755, true);
        $this->createTheme('default', '默认主题');
        $this->createTheme('blog', '博客主题');
    }

    protected function tearDown(): void
    {
        $this->files->deleteDirectory($this->themesPath);

        parent::tearDown();
    }

    public function test_uses_active_theme_from_site_setting(): void
    {
        Setting::create(['group' => 'site', 'key' => 'active_theme', 'value' => 'blog']);

        $resolver = $this->makeResolver();

        $this->assertSame('blog', $resolver->active()->id);
    }

    public function test_falls_back_to_default_when_active_theme_missing(): void
    {
        Setting::create(['group' => 'site', 'key' => 'active_theme', 'value' => 'missing']);

        $resolver = $this->makeResolver();

        $this->assertSame('default', $resolver->active()->id);
    }

    public function test_preview_query_overrides_active_theme_for_super_admin(): void
    {
        Setting::create(['group' => 'site', 'key' => 'active_theme', 'value' => 'default']);

        $user = User::factory()->create();
        Role::findOrCreate('super_admin', 'web');
        $user->assignRole('super_admin');

        $this->actingAs($user);
        request()->query->set('theme', 'blog');

        $resolver = $this->makeResolver();

        $this->assertSame('blog', $resolver->active()->id);
    }

    public function test_preview_query_is_ignored_for_non_super_admin(): void
    {
        Setting::create(['group' => 'site', 'key' => 'active_theme', 'value' => 'default']);

        $user = User::factory()->create();
        Role::findOrCreate('member', 'web');
        $user->assignRole('member');

        $this->actingAs($user);
        request()->query->set('theme', 'blog');

        $resolver = $this->makeResolver();

        $this->assertSame('default', $resolver->active()->id);
    }

    public function test_preview_query_is_ignored_for_guests(): void
    {
        Setting::create(['group' => 'site', 'key' => 'active_theme', 'value' => 'default']);

        request()->query->set('theme', 'blog');

        $resolver = $this->makeResolver();

        $this->assertSame('default', $resolver->active()->id);
    }

    public function test_default_method_returns_default_theme(): void
    {
        $resolver = $this->makeResolver();

        $this->assertSame('default', $resolver->default()->id);
    }

    private function createTheme(string $id, string $label): Theme
    {
        $path = $this->themesPath.'/'.$id;
        $this->files->makeDirectory($path, 0755, true);
        $this->files->put($path.'/theme.json', json_encode([
            'name' => $id,
            'label' => $label,
            'version' => '1.0.0',
            'author' => 'Test',
            'preview' => null,
        ]));

        return new Theme($id, $id, $label, null, '1.0.0', 'Test', null);
    }

    private function makeResolver(): ThemeResolver
    {
        $registry = new ThemeRegistry($this->themesPath, $this->files);

        return new ThemeResolver($registry);
    }
}
