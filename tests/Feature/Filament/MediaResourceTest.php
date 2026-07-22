<?php

namespace Tests\Feature\Filament;

use App\Enums\ContentStatus;
use App\Filament\Admin\Resources\Articles\Pages\CreateArticle;
use App\Filament\Admin\Resources\MediaResource\Pages\EditMedia;
use App\Filament\Admin\Resources\MediaResource\Pages\ListMedia;
use App\Models\Article;
use App\Models\MediaCategory;
use App\Models\MediaLibrary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MediaResourceTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Role::create(['name' => 'admin']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    public function test_can_render_media_list(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(ListMedia::class)
            ->assertSuccessful();
    }

    public function test_can_upload_media(): void
    {
        $this->actingAs($this->admin);
        Storage::disk('public')->put('media-library/photo1.jpg', UploadedFile::fake()->image('photo1.jpg')->get());
        Storage::disk('public')->put('media-library/photo2.jpg', UploadedFile::fake()->image('photo2.jpg')->get());

        Livewire::test(ListMedia::class)
            ->callAction('create', [
                'files' => [
                    'media-library/photo1.jpg',
                    'media-library/photo2.jpg',
                ],
            ]);

        $this->assertDatabaseCount('media', 2);
    }

    public function test_can_edit_media_category(): void
    {
        $this->actingAs($this->admin);
        $category = MediaCategory::factory()->create();
        $library = MediaLibrary::singleton();
        $media = $library->addMedia(UploadedFile::fake()->image('photo.jpg'))->toMediaCollection('media_library');

        Livewire::test(EditMedia::class, ['record' => $media->id])
            ->fillForm([
                'name' => 'Updated Photo',
                'media_category_id' => $category->id,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('media', [
            'id' => $media->id,
            'name' => 'Updated Photo',
            'media_category_id' => $category->id,
        ]);
    }

    public function test_can_delete_unreferenced_media(): void
    {
        $this->actingAs($this->admin);
        $library = MediaLibrary::singleton();
        $media = $library->addMedia(UploadedFile::fake()->image('photo.jpg'))->toMediaCollection('media_library');

        Livewire::test(ListMedia::class)
            ->callTableAction('delete', $media->id);

        $this->assertModelMissing($media);
    }

    public function test_cannot_delete_referenced_media(): void
    {
        $this->actingAs($this->admin);
        $article = Article::factory()->create();
        $media = $article->addMedia(UploadedFile::fake()->image('photo.jpg'))->toMediaCollection('cover');

        Livewire::test(ListMedia::class)
            ->callTableAction('delete', $media->id);

        $this->assertModelExists($media);
    }

    public function test_article_can_select_cover_from_media_library(): void
    {
        $this->actingAs($this->admin);
        $library = MediaLibrary::singleton();
        $media = $library->addMedia(UploadedFile::fake()->image('photo.jpg'))->toMediaCollection('media_library');

        Livewire::test(CreateArticle::class)
            ->fillForm([
                'title' => '文章标题',
                'content' => '<p>正文</p>',
                'status' => ContentStatus::Published->value,
                'user_id' => $this->admin->id,
                'media_library_id' => $media->id,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $article = Article::first();
        $this->assertNotNull($article);
        $this->assertTrue($article->getMedia('cover')->isNotEmpty());
    }
}
