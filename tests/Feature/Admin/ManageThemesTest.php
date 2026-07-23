<?php

namespace Tests\Feature\Admin;

use App\Filament\Admin\Pages\ManageThemes;
use App\Models\User;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ManageThemesTest extends TestCase
{
    use RefreshDatabase;

    private Filesystem $files;

    private ?string $originalManifest = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->files = new Filesystem;
        $this->ensureTestThemeRemoved();
        $this->createTestTheme();
        $this->stubManifest();
    }

    protected function tearDown(): void
    {
        $this->ensureTestThemeRemoved();

        $manifestPath = public_path('build/manifest.json');
        if ($this->originalManifest !== null) {
            $this->files->put($manifestPath, $this->originalManifest);
        } elseif ($this->files->exists($manifestPath)) {
            $this->files->delete($manifestPath);
        }

        parent::tearDown();
    }

    public function test_super_admin_can_visit_theme_management_page(): void
    {
        $user = $this->makeSuperAdmin();

        $response = $this->actingAs($user)->get('/admin/manage-themes');

        $response->assertOk();
        $response->assertSee('测试博客主题');
    }

    public function test_non_super_admin_cannot_visit_theme_management_page(): void
    {
        $user = User::factory()->create();
        Role::findOrCreate('member', 'web');
        $user->assignRole('member');

        $response = $this->actingAs($user)->get('/admin/manage-themes');

        $response->assertForbidden();
    }

    public function test_guest_cannot_visit_theme_management_page(): void
    {
        $response = $this->get('/admin/manage-themes');

        $response->assertRedirect('/admin/login');
    }

    public function test_super_admin_can_activate_compiled_theme(): void
    {
        $user = $this->makeSuperAdmin();

        Livewire::actingAs($user)
            ->test(ManageThemes::class)
            ->call('activate', 'test-blog')
            ->assertOk();

        $this->assertDatabaseHas('settings', [
            'group' => 'site',
            'key' => 'active_theme',
            'value' => 'test-blog',
        ]);
    }

    public function test_uncompiled_theme_cannot_be_activated(): void
    {
        $user = $this->makeSuperAdmin();

        $manifestPath = public_path('build/manifest.json');
        $manifest = json_decode($this->files->get($manifestPath), true);
        unset($manifest['resources/themes/test-blog/css/app.css']);
        unset($manifest['resources/themes/test-blog/js/app.js']);
        $this->files->put($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT));

        Livewire::actingAs($user)
            ->test(ManageThemes::class)
            ->call('activate', 'test-blog')
            ->assertOk();

        $this->assertDatabaseMissing('settings', [
            'group' => 'site',
            'key' => 'active_theme',
            'value' => 'test-blog',
        ]);
    }

    private function makeSuperAdmin(): User
    {
        $user = User::factory()->create();
        Role::findOrCreate('super_admin', 'web');
        $user->assignRole('super_admin');

        return $user;
    }

    private function createTestTheme(): void
    {
        $base = resource_path('themes/test-blog');

        if (! $this->files->isDirectory($base.'/views')) {
            $this->files->makeDirectory($base.'/views', 0755, true);
        }

        $this->files->put($base.'/theme.json', json_encode([
            'name' => 'test-blog',
            'label' => '测试博客主题',
            'version' => '1.0.0',
            'author' => 'Test',
            'preview' => null,
        ]));
    }

    private function stubManifest(): void
    {
        $manifestPath = public_path('build/manifest.json');
        $this->originalManifest = $this->files->exists($manifestPath)
            ? $this->files->get($manifestPath)
            : null;

        if (! $this->files->isDirectory(public_path('build'))) {
            $this->files->makeDirectory(public_path('build'), 0755, true);
        }

        $this->files->put($manifestPath, json_encode([
            'resources/themes/default/css/app.css' => ['file' => 'assets/default-app.css'],
            'resources/themes/default/js/app.js' => ['file' => 'assets/default-app.js'],
            'resources/themes/test-blog/css/app.css' => ['file' => 'assets/test-blog-app.css'],
            'resources/themes/test-blog/js/app.js' => ['file' => 'assets/test-blog-app.js'],
        ], JSON_PRETTY_PRINT));
    }

    private function ensureTestThemeRemoved(): void
    {
        $base = resource_path('themes/test-blog');

        if ($this->files->isDirectory($base)) {
            $this->files->deleteDirectory($base);
        }
    }
}
