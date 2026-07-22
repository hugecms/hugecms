<?php

namespace Tests\Feature\Filament;

use App\Filament\Admin\Resources\Tags\Pages\CreateTag;
use App\Filament\Admin\Resources\Tags\Pages\EditTag;
use App\Filament\Admin\Resources\Tags\Pages\ListTags;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TagResourceTest extends TestCase
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

    public function test_can_render_tag_list(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(ListTags::class)
            ->assertSuccessful();
    }

    public function test_can_create_tag(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(CreateTag::class)
            ->fillForm([
                'name' => '测试标签',
                'slug' => 'test-tag',
                'description' => '标签描述',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('tags', [
            'name' => '测试标签',
            'slug' => 'test-tag',
        ]);
    }

    public function test_can_edit_tag(): void
    {
        $this->actingAs($this->admin);
        $tag = Tag::factory()->create();

        Livewire::test(EditTag::class, ['record' => $tag->id])
            ->fillForm([
                'name' => '更新后的标签',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('tags', [
            'id' => $tag->id,
            'name' => '更新后的标签',
        ]);
    }

    public function test_can_delete_tag(): void
    {
        $this->actingAs($this->admin);
        $tag = Tag::factory()->create();

        Livewire::test(EditTag::class, ['record' => $tag->id])
            ->callAction('delete');

        $this->assertModelMissing($tag);
    }
}
