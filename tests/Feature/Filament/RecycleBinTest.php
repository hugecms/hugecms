<?php

namespace Tests\Feature\Filament;

use App\Filament\Admin\Pages\RecycleBin;
use App\Models\Article;
use App\Models\Page;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class RecycleBinTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = $this->createSuperAdmin();
    }

    protected function actingAsRole(string $role): User
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $this->seed([PermissionSeeder::class, RoleSeeder::class]);

        $user = User::factory()->create();
        $user->assignRole($role);
        $this->actingAs($user);

        return $user;
    }

    public function test_can_render_recycle_bin(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(RecycleBin::class)
            ->assertSuccessful();
    }

    public function test_article_tab_shows_only_trashed_articles(): void
    {
        $this->actingAs($this->admin);
        $trashed = Article::factory()->create(['title' => '回收站中的文章', 'deleted_at' => now()]);
        $active = Article::factory()->create(['title' => '未删除的文章']);
        $trashedPage = Page::factory()->create(['title' => '回收站中的页面', 'deleted_at' => now()]);

        // 文章与页面主键会碰撞(各自表的 id 1),不可见断言用标题文本而非记录 key
        Livewire::test(RecycleBin::class)
            ->assertCanSeeTableRecords([$trashed])
            ->assertDontSeeText($active->title)
            ->assertDontSeeText($trashedPage->title);
    }

    public function test_switching_to_page_tab_shows_trashed_pages(): void
    {
        $this->actingAs($this->admin);
        $trashedArticle = Article::factory()->create(['title' => '回收站中的文章', 'deleted_at' => now()]);
        $trashedPage = Page::factory()->create(['title' => '回收站中的页面', 'deleted_at' => now()]);
        $activePage = Page::factory()->create(['title' => '未删除的页面']);

        Livewire::test(RecycleBin::class)
            ->call('setTab', 'page')
            ->assertSet('activeTab', 'page')
            ->assertCanSeeTableRecords([$trashedPage])
            ->assertDontSeeText($trashedArticle->title)
            ->assertDontSeeText($activePage->title);
    }

    public function test_can_restore_article(): void
    {
        $this->actingAs($this->admin);
        $article = Article::factory()->create(['deleted_at' => now()]);

        Livewire::test(RecycleBin::class)
            ->callTableAction('restore', $article->id);

        $this->assertNull($article->refresh()->deleted_at);
    }

    public function test_can_restore_page(): void
    {
        $this->actingAs($this->admin);
        $page = Page::factory()->create(['deleted_at' => now()]);

        Livewire::test(RecycleBin::class)
            ->call('setTab', 'page')
            ->callTableAction('restore', $page->id);

        $this->assertNull($page->refresh()->deleted_at);
    }

    public function test_can_force_delete_article(): void
    {
        $this->actingAs($this->admin);
        $article = Article::factory()->create(['deleted_at' => now()]);

        Livewire::test(RecycleBin::class)
            ->callTableAction('forceDelete', $article->id);

        $this->assertModelMissing($article);
    }

    public function test_deleted_at_date_filter_works(): void
    {
        $this->actingAs($this->admin);
        $recent = Article::factory()->create(['deleted_at' => now()]);
        $old = Article::factory()->create(['deleted_at' => now()->subMonths(2)]);

        Livewire::test(RecycleBin::class)
            ->filterTable('deleted_at', [
                'from' => now()->subWeek()->toDateString(),
                'until' => now()->toDateString(),
            ])
            ->assertCanSeeTableRecords([$recent])
            ->assertCanNotSeeTableRecords([$old]);
    }

    public function test_auditor_sees_records_but_no_restore_or_force_delete_actions(): void
    {
        $this->actingAsRole('auditor');

        $article = Article::factory()->create(['deleted_at' => now()]);

        Livewire::test(RecycleBin::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords([$article])
            ->assertTableActionHidden('restore', $article->id)
            ->assertTableActionHidden('forceDelete', $article->id);
    }

    public function test_customer_service_cannot_restore_or_force_delete(): void
    {
        $this->actingAsRole('customer_service');

        $article = Article::factory()->create(['deleted_at' => now()]);

        Livewire::test(RecycleBin::class)
            ->assertSuccessful()
            ->assertTableActionHidden('restore', $article->id)
            ->assertTableActionHidden('forceDelete', $article->id);
    }

    public function test_user_without_view_permissions_cannot_access(): void
    {
        $this->actingAsRole('member');

        $this->assertFalse(RecycleBin::canAccess());

        $this->actingAsRole('editor');

        $this->assertTrue(RecycleBin::canAccess());
    }

    public function test_cannot_switch_to_tab_without_view_permission(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $this->seed(PermissionSeeder::class);

        $role = Role::findOrCreate('page_only', 'web');
        $role->syncPermissions(['page.view_any', 'access_admin']);

        $user = User::factory()->create();
        $user->assignRole($role);
        $this->actingAs($user);

        $trashedArticle = Article::factory()->create(['title' => '回收站中的文章', 'deleted_at' => now()]);
        Page::factory()->create(['deleted_at' => now()]);

        Livewire::test(RecycleBin::class)
            ->assertSet('activeTab', 'page')
            ->call('setTab', 'article')
            ->assertSet('activeTab', 'page')
            ->assertDontSeeText($trashedArticle->title);
    }
}
