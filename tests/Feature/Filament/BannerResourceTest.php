<?php

namespace Tests\Feature\Filament;

use App\Enums\PublishStatus;
use App\Filament\Admin\Resources\Banners\Pages\CreateBanner;
use App\Filament\Admin\Resources\Banners\Pages\EditBanner;
use App\Filament\Admin\Resources\Banners\Pages\ListBanners;
use App\Models\Banner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BannerResourceTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = $this->createSuperAdmin();
    }

    public function test_can_render_banner_list(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(ListBanners::class)
            ->assertSuccessful();
    }

    public function test_can_create_banner(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(CreateBanner::class)
            ->fillForm([
                'title' => '首页 Banner',
                'link' => 'https://example.com',
                'sort_order' => 1,
                'status' => PublishStatus::Published->value,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('banners', [
            'title' => '首页 Banner',
            'link' => 'https://example.com',
            'sort_order' => 1,
        ]);
    }

    public function test_can_edit_banner(): void
    {
        $this->actingAs($this->admin);
        $banner = Banner::factory()->create();

        Livewire::test(EditBanner::class, ['record' => $banner->id])
            ->fillForm([
                'title' => '更新后的 Banner',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('banners', [
            'id' => $banner->id,
            'title' => '更新后的 Banner',
        ]);
    }

    public function test_can_delete_banner(): void
    {
        $this->actingAs($this->admin);
        $banner = Banner::factory()->create();

        Livewire::test(EditBanner::class, ['record' => $banner->id])
            ->callAction('delete');

        $this->assertModelMissing($banner);
    }
}
