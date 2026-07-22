<?php

namespace Tests\Feature\Filament;

use App\Enums\UserStatus;
use App\Filament\Admin\Resources\Users\Pages\CreateUser;
use App\Filament\Admin\Resources\Users\Pages\EditUser;
use App\Filament\Admin\Resources\Users\Pages\ListUsers;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserResourceTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = $this->createSuperAdmin();
    }

    public function test_can_render_user_list(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(ListUsers::class)
            ->assertSuccessful();
    }

    public function test_can_create_user_with_role(): void
    {
        $this->actingAs($this->admin);
        $role = Role::findOrCreate('editor', 'web');

        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => '测试编辑',
                'email' => 'editor@example.com',
                'phone' => '13800000000',
                'password' => 'password123',
                'status' => 'active',
                'roles' => [$role->id],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $user = User::where('email', 'editor@example.com')->first();

        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('editor'));
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_can_edit_user_without_changing_password(): void
    {
        $this->actingAs($this->admin);
        $user = User::factory()->create();
        $originalPassword = $user->password;

        Livewire::test(EditUser::class, ['record' => $user->id])
            ->fillForm([
                'name' => '更新后的名字',
                'password' => '',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $user->refresh();

        $this->assertSame('更新后的名字', $user->name);
        $this->assertSame($originalPassword, $user->password);
    }

    public function test_can_disable_and_enable_user(): void
    {
        $this->actingAs($this->admin);
        $user = User::factory()->create();

        Livewire::test(ListUsers::class)
            ->callTableAction('toggleStatus', $user->id);

        $this->assertSame(UserStatus::Disabled, $user->refresh()->status);

        Livewire::test(ListUsers::class)
            ->callTableAction('toggleStatus', $user->id);

        $this->assertSame(UserStatus::Active, $user->refresh()->status);
    }

    public function test_cannot_delete_self(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(ListUsers::class)
            ->assertTableActionHidden('delete', $this->admin->id)
            ->assertTableActionHidden('toggleStatus', $this->admin->id);
    }

    public function test_can_delete_other_user(): void
    {
        $this->actingAs($this->admin);
        $user = User::factory()->create();

        Livewire::test(ListUsers::class)
            ->callTableAction('delete', $user->id);

        $this->assertModelMissing($user);
    }

    public function test_role_and_status_filters_work(): void
    {
        $this->actingAs($this->admin);
        $role = Role::findOrCreate('editor', 'web');
        $editor = User::factory()->create();
        $editor->assignRole($role);
        $disabled = User::factory()->disabled()->create();

        Livewire::test(ListUsers::class)
            ->filterTable('roles', $role->id)
            ->assertCanSeeTableRecords(User::role('editor')->get())
            ->assertCanNotSeeTableRecords(User::whereDoesntHave('roles')->whereNot('id', $this->admin->id)->get());

        Livewire::test(ListUsers::class)
            ->filterTable('status', 'disabled')
            ->assertCanSeeTableRecords([$disabled])
            ->assertCanNotSeeTableRecords(User::where('status', 'active')->get());
    }
}
