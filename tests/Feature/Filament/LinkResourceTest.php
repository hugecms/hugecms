<?php

namespace Tests\Feature\Filament;

use App\Enums\PublishStatus;
use App\Filament\Admin\Resources\Links\Pages\CreateLink;
use App\Filament\Admin\Resources\Links\Pages\EditLink;
use App\Filament\Admin\Resources\Links\Pages\ListLinks;
use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LinkResourceTest extends TestCase
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

    public function test_can_render_link_list(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(ListLinks::class)
            ->assertSuccessful();
    }

    public function test_can_create_link(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(CreateLink::class)
            ->fillForm([
                'name' => '友链名称',
                'link' => 'https://example.com',
                'sort_order' => 1,
                'status' => PublishStatus::Published->value,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('links', [
            'name' => '友链名称',
            'link' => 'https://example.com',
            'sort_order' => 1,
        ]);
    }

    public function test_can_edit_link(): void
    {
        $this->actingAs($this->admin);
        $link = Link::factory()->create();

        Livewire::test(EditLink::class, ['record' => $link->id])
            ->fillForm([
                'name' => '更新后的友链',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('links', [
            'id' => $link->id,
            'name' => '更新后的友链',
        ]);
    }

    public function test_can_delete_link(): void
    {
        $this->actingAs($this->admin);
        $link = Link::factory()->create();

        Livewire::test(EditLink::class, ['record' => $link->id])
            ->callAction('delete');

        $this->assertModelMissing($link);
    }
}
