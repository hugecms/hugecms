<?php

namespace Tests\Feature\Filament;

use App\Enums\ContentStatus;
use App\Filament\Admin\Resources\Pages\Pages\CreatePage;
use App\Filament\Admin\Resources\Pages\Pages\EditPage;
use App\Filament\Admin\Resources\Pages\Pages\ListPages;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PageResourceTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    public function test_can_render_page_list(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(ListPages::class)
            ->assertSuccessful();
    }

    public function test_can_create_page(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(CreatePage::class)
            ->fillForm([
                'title' => '测试页面',
                'slug' => 'test-page',
                'content' => '<p>页面内容</p>',
                'template' => 'default',
                'status' => ContentStatus::Published->value,
                'user_id' => $this->admin->id,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('pages', [
            'title' => '测试页面',
            'slug' => 'test-page',
            'template' => 'default',
        ]);
    }

    public function test_can_edit_page(): void
    {
        $this->actingAs($this->admin);
        $page = Page::factory()->create();

        Livewire::test(EditPage::class, ['record' => $page->id])
            ->fillForm([
                'title' => '更新后的页面标题',
                'template' => 'default',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'title' => '更新后的页面标题',
        ]);
    }

    public function test_can_soft_delete_page(): void
    {
        $this->actingAs($this->admin);
        $page = Page::factory()->create();

        Livewire::test(EditPage::class, ['record' => $page->id])
            ->callAction('delete');

        $this->assertSoftDeleted($page);
    }

    public function test_can_restore_page(): void
    {
        $this->actingAs($this->admin);
        $page = Page::factory()->create();
        $page->delete();

        Livewire::test(ListPages::class)
            ->filterTable('trashed', 'only')
            ->callTableAction('restore', $page->id);

        $this->assertModelExists($page);
    }
}
