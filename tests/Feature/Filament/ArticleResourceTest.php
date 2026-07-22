<?php

namespace Tests\Feature\Filament;

use App\Enums\ContentStatus;
use App\Filament\Admin\Resources\Articles\Pages\CreateArticle;
use App\Filament\Admin\Resources\Articles\Pages\EditArticle;
use App\Filament\Admin\Resources\Articles\Pages\ListArticles;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ArticleResourceTest extends TestCase
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

    public function test_can_render_article_list(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(ListArticles::class)
            ->assertSuccessful();
    }

    public function test_can_create_article(): void
    {
        $this->actingAs($this->admin);
        $category = Category::factory()->create();
        $tag = Tag::factory()->create();

        Livewire::test(CreateArticle::class)
            ->fillForm([
                'title' => '测试文章',
                'slug' => 'test-article',
                'excerpt' => '摘要内容',
                'content' => '<p>正文内容</p>',
                'status' => ContentStatus::Published->value,
                'user_id' => $this->admin->id,
                'categories' => [$category->id],
                'tags' => [$tag->id],
                'is_pinned' => true,
                'is_recommended' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('articles', [
            'title' => '测试文章',
            'slug' => 'test-article',
            'is_pinned' => true,
            'is_recommended' => true,
        ]);
    }

    public function test_slug_is_auto_generated_from_title(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(CreateArticle::class)
            ->fillForm([
                'title' => '自动生成的 Slug',
                'content' => '<p>正文</p>',
                'status' => ContentStatus::Published->value,
                'user_id' => $this->admin->id,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('articles', [
            'title' => '自动生成的 Slug',
            'slug' => 'zi-dong-sheng-cheng-de-slug',
        ]);
    }

    public function test_can_edit_article(): void
    {
        $this->actingAs($this->admin);
        $article = Article::factory()->create();

        Livewire::test(EditArticle::class, ['record' => $article->id])
            ->fillForm([
                'title' => '更新后的标题',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => '更新后的标题',
        ]);
    }

    public function test_can_soft_delete_article(): void
    {
        $this->actingAs($this->admin);
        $article = Article::factory()->create();

        Livewire::test(EditArticle::class, ['record' => $article->id])
            ->callAction('delete');

        $this->assertSoftDeleted($article);
    }

    public function test_can_restore_article(): void
    {
        $this->actingAs($this->admin);
        $article = Article::factory()->create();
        $article->delete();

        Livewire::test(ListArticles::class)
            ->filterTable('trashed', 'only')
            ->callTableAction('restore', $article->id);

        $this->assertModelExists($article);
    }

    public function test_status_filter_works(): void
    {
        $this->actingAs($this->admin);
        Article::factory()->count(3)->create(['status' => ContentStatus::Published->value]);
        Article::factory()->count(2)->create(['status' => ContentStatus::Draft->value]);

        Livewire::test(ListArticles::class)
            ->assertCanSeeTableRecords(Article::where('status', ContentStatus::Published->value)->get())
            ->filterTable('status', ContentStatus::Draft->value)
            ->assertCanSeeTableRecords(Article::where('status', ContentStatus::Draft->value)->get())
            ->assertCanNotSeeTableRecords(Article::where('status', ContentStatus::Published->value)->get());
    }
}
