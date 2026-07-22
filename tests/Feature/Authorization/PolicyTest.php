<?php

namespace Tests\Feature\Authorization;

use App\Filament\Admin\Resources\Articles\Pages\CreateArticle;
use App\Filament\Admin\Resources\Articles\Pages\ListArticles;
use App\Filament\Admin\Resources\Comments\Pages\ListComments;
use App\Filament\Admin\Resources\Roles\Pages\ListRoles;
use App\Filament\Admin\Resources\Users\Pages\ListUsers;
use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class PolicyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $this->seed([PermissionSeeder::class, RoleSeeder::class]);
    }

    protected function userWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    public function test_editor_can_manage_articles(): void
    {
        $editor = $this->userWithRole('editor');
        $this->actingAs($editor);

        $this->assertTrue($editor->can('article.view_any'));
        $this->assertTrue($editor->can('article.create'));

        Livewire::test(ListArticles::class)->assertSuccessful();
        Livewire::test(CreateArticle::class)->assertSuccessful();
    }

    public function test_editor_cannot_access_user_management(): void
    {
        $editor = $this->userWithRole('editor');
        $this->actingAs($editor);

        $this->assertFalse($editor->can('user.view_any'));

        Livewire::test(ListUsers::class)->assertForbidden();
    }

    public function test_auditor_can_view_but_not_create_articles(): void
    {
        $auditor = $this->userWithRole('auditor');
        $this->actingAs($auditor);

        $this->assertTrue($auditor->can('viewAny', Article::class));
        $this->assertFalse($auditor->can('create', Article::class));

        Livewire::test(ListArticles::class)->assertSuccessful();
        Livewire::test(CreateArticle::class)->assertForbidden();
    }

    public function test_customer_service_can_approve_comments(): void
    {
        $customerService = $this->userWithRole('customer_service');
        $this->actingAs($customerService);

        $comment = Comment::factory()->pending()->create();

        Livewire::test(ListComments::class)
            ->assertSuccessful()
            ->callTableAction('approve', $comment->id);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'status' => 'approved',
        ]);
    }

    public function test_customer_service_cannot_manage_users(): void
    {
        $customerService = $this->userWithRole('customer_service');
        $this->actingAs($customerService);

        $this->assertFalse($customerService->can('user.view_any'));

        Livewire::test(ListUsers::class)->assertForbidden();
    }

    public function test_operator_can_manage_banners_but_not_articles_or_users(): void
    {
        $operator = $this->userWithRole('operator');
        $this->actingAs($operator);

        $this->assertTrue($operator->can('banner.view_any'));
        $this->assertTrue($operator->can('banner.create'));
        $this->assertTrue($operator->can('article.view_any'));
        $this->assertFalse($operator->can('article.create'));
        $this->assertFalse($operator->can('user.view_any'));

        Livewire::test(ListUsers::class)->assertForbidden();
    }

    public function test_admin_role_can_access_role_management(): void
    {
        $admin = $this->userWithRole('admin');
        $this->actingAs($admin);

        $this->assertTrue($admin->can('role.view_any'));

        Livewire::test(ListRoles::class)->assertSuccessful();
    }

    public function test_editor_cannot_access_role_management(): void
    {
        $editor = $this->userWithRole('editor');
        $this->actingAs($editor);

        $this->assertFalse($editor->can('role.view_any'));

        Livewire::test(ListRoles::class)->assertForbidden();
    }

    public function test_super_admin_bypasses_all_policies(): void
    {
        $superAdmin = $this->userWithRole('super_admin');
        $this->actingAs($superAdmin);

        $this->assertTrue($superAdmin->can('user.view_any'));
        $this->assertTrue($superAdmin->can('role.delete'));
        $this->assertTrue($superAdmin->can('create', Article::class));

        Livewire::test(ListUsers::class)->assertSuccessful();
        Livewire::test(CreateArticle::class)->assertSuccessful();
    }

    public function test_user_without_admin_access_cannot_access_panel(): void
    {
        $member = $this->userWithRole('member');

        $this->assertFalse($member->canAccessPanel(
            Filament::getPanel('admin')
        ));
    }

    public function test_disabled_user_cannot_access_panel(): void
    {
        $disabledEditor = User::factory()->disabled()->create();
        $disabledEditor->assignRole('editor');

        $this->assertFalse($disabledEditor->canAccessPanel(
            Filament::getPanel('admin')
        ));
    }
}
