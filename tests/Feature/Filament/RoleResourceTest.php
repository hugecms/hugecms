<?php

namespace Tests\Feature\Filament;

use App\Filament\Admin\Resources\Roles\Pages\CreateRole;
use App\Filament\Admin\Resources\Roles\Pages\EditRole;
use App\Filament\Admin\Resources\Roles\Pages\ListRoles;
use App\Models\User;
use App\Support\Permissions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleResourceTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = $this->createSuperAdmin();
    }

    public function test_can_render_role_list(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(ListRoles::class)
            ->assertSuccessful();
    }

    public function test_can_create_role_with_permissions(): void
    {
        $this->actingAs($this->admin);
        $permissions = collect(['article.view_any', 'article.create'])
            ->map(fn (string $name) => Permission::findOrCreate($name, 'web'));

        Livewire::test(CreateRole::class)
            ->fillForm([
                'name' => 'custom_role',
                'permissions' => $permissions->pluck('id')->toArray(),
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $role = Role::where('name', 'custom_role')->first();

        $this->assertNotNull($role);
        $this->assertTrue($role->hasPermissionTo('article.view_any'));
        $this->assertTrue($role->hasPermissionTo('article.create'));
        $this->assertSame(2, $role->permissions()->count());
    }

    public function test_can_edit_role_permissions(): void
    {
        $this->actingAs($this->admin);
        $role = Role::findOrCreate('editor', 'web');
        $permission = Permission::findOrCreate('comment.view_any', 'web');

        Livewire::test(EditRole::class, ['record' => $role->id])
            ->fillForm([
                'permissions' => [$permission->id],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $role->refresh();

        $this->assertTrue($role->hasPermissionTo('comment.view_any'));
    }

    public function test_super_admin_role_cannot_be_deleted(): void
    {
        $this->actingAs($this->admin);
        $superAdmin = Role::where('name', 'super_admin')->firstOrFail();
        $other = Role::findOrCreate('editor', 'web');

        Livewire::test(ListRoles::class)
            ->assertTableActionHidden('delete', $superAdmin->id)
            ->assertTableActionVisible('delete', $other->id);
    }

    public function test_permissions_catalog_covers_all_seeded_permissions(): void
    {
        foreach (Permissions::names() as $name) {
            $this->assertNotSame($name, Permissions::label($name));
        }
    }
}
