<?php

namespace Tests\Feature\Filament;

use App\Filament\Admin\Pages\ManageSiteSettings;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class ManageSiteSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = $this->createSuperAdmin();
        $this->actingAs($this->admin);
    }

    public function test_can_render_site_settings_page(): void
    {
        Livewire::test(ManageSiteSettings::class)
            ->assertSuccessful();
    }

    public function test_can_save_site_settings(): void
    {
        Livewire::test(ManageSiteSettings::class)
            ->fillForm([
                'site_name' => 'HugeCMS 测试站',
                'icp' => '京ICP备12345678号',
                'copyright' => '© 2026 HugeCMS',
                'contact' => 'contact@example.com',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('settings', [
            'group' => 'site',
            'key' => 'site_name',
            'value' => 'HugeCMS 测试站',
        ]);
    }

    public function test_editor_cannot_access_site_settings(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $this->seed([PermissionSeeder::class, RoleSeeder::class]);

        $editor = User::factory()->create();
        $editor->assignRole('editor');
        $this->actingAs($editor);

        $this->assertFalse(ManageSiteSettings::canAccess());

        Livewire::test(ManageSiteSettings::class)->assertForbidden();
    }
}
