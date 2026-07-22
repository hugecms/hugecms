<?php

namespace Tests\Feature\Filament;

use App\Enums\PublishStatus;
use App\Filament\Admin\Resources\Announcements\Pages\CreateAnnouncement;
use App\Filament\Admin\Resources\Announcements\Pages\EditAnnouncement;
use App\Filament\Admin\Resources\Announcements\Pages\ListAnnouncements;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AnnouncementResourceTest extends TestCase
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

    public function test_can_render_announcement_list(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(ListAnnouncements::class)
            ->assertSuccessful();
    }

    public function test_can_create_announcement(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(CreateAnnouncement::class)
            ->fillForm([
                'title' => '系统公告',
                'content' => '<p>公告内容</p>',
                'type' => 'info',
                'status' => PublishStatus::Published->value,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('announcements', [
            'title' => '系统公告',
            'type' => 'info',
        ]);
    }

    public function test_can_edit_announcement(): void
    {
        $this->actingAs($this->admin);
        $announcement = Announcement::factory()->create();

        Livewire::test(EditAnnouncement::class, ['record' => $announcement->id])
            ->fillForm([
                'title' => '更新后的公告',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('announcements', [
            'id' => $announcement->id,
            'title' => '更新后的公告',
        ]);
    }

    public function test_can_delete_announcement(): void
    {
        $this->actingAs($this->admin);
        $announcement = Announcement::factory()->create();

        Livewire::test(EditAnnouncement::class, ['record' => $announcement->id])
            ->callAction('delete');

        $this->assertModelMissing($announcement);
    }
}
