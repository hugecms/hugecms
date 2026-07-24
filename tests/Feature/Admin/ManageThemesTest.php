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

    protected function setUp(): void
    {
        parent::setUp();

        $this->files = new Filesystem;
        $this->ensureTestThemeRemoved();
        $this->createTestTheme();
    }

    protected function tearDown(): void
    {
        $this->ensureTestThemeRemoved();

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

    public function test_super_admin_can_activate_theme(): void
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

    private function ensureTestThemeRemoved(): void
    {
        $base = resource_path('themes/test-blog');

        if ($this->files->isDirectory($base)) {
            $this->files->deleteDirectory($base);
        }
    }
}
