<?php

namespace Tests\Feature\Filament;

use App\Filament\Admin\Resources\Categories\Pages\CreateCategory;
use App\Filament\Admin\Resources\Categories\Pages\EditCategory;
use App\Filament\Admin\Resources\Categories\Pages\ListCategories;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CategoryResourceTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = $this->createSuperAdmin();
    }

    public function test_can_render_category_list(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(ListCategories::class)
            ->assertSuccessful();
    }

    public function test_can_create_category(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(CreateCategory::class)
            ->fillForm([
                'name' => '测试分类',
                'slug' => 'test-category',
                'description' => '分类描述',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('categories', [
            'name' => '测试分类',
            'slug' => 'test-category',
        ]);
    }

    public function test_can_create_child_category(): void
    {
        $this->actingAs($this->admin);
        $parent = Category::factory()->create();

        Livewire::test(CreateCategory::class)
            ->fillForm([
                'name' => '子分类',
                'slug' => 'child-category',
                'parent_id' => $parent->id,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('categories', [
            'name' => '子分类',
            'parent_id' => $parent->id,
        ]);
    }

    public function test_can_edit_category(): void
    {
        $this->actingAs($this->admin);
        $category = Category::factory()->create();

        Livewire::test(EditCategory::class, ['record' => $category->id])
            ->fillForm([
                'name' => '更新后的分类',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => '更新后的分类',
        ]);
    }

    public function test_can_delete_category(): void
    {
        $this->actingAs($this->admin);
        $category = Category::factory()->create();

        Livewire::test(EditCategory::class, ['record' => $category->id])
            ->callAction('delete');

        $this->assertModelMissing($category);
    }
}
