<?php

namespace Tests\Feature\Filament;

use App\Enums\CommentStatus;
use App\Filament\Admin\Resources\Comments\Pages\CreateComment;
use App\Filament\Admin\Resources\Comments\Pages\EditComment;
use App\Filament\Admin\Resources\Comments\Pages\ListComments;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CommentResourceTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = $this->createSuperAdmin();
    }

    public function test_can_render_comment_list(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(ListComments::class)
            ->assertSuccessful();
    }

    public function test_can_create_comment(): void
    {
        $this->actingAs($this->admin);
        $article = Article::factory()->create();

        Livewire::test(CreateComment::class)
            ->fillForm([
                'content' => '这是一条测试评论',
                'status' => CommentStatus::Approved->value,
                'article_id' => $article->id,
                'user_id' => $this->admin->id,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('comments', [
            'content' => '这是一条测试评论',
            'status' => CommentStatus::Approved->value,
            'article_id' => $article->id,
            'user_id' => $this->admin->id,
        ]);
    }

    public function test_can_edit_comment(): void
    {
        $this->actingAs($this->admin);
        $comment = Comment::factory()->create();

        Livewire::test(EditComment::class, ['record' => $comment->id])
            ->fillForm([
                'content' => '更新后的评论内容',
                'status' => CommentStatus::Approved->value,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => '更新后的评论内容',
            'status' => CommentStatus::Approved->value,
        ]);
    }

    public function test_status_filter_works(): void
    {
        $this->actingAs($this->admin);
        Comment::factory()->count(3)->approved()->create();
        Comment::factory()->count(2)->pending()->create();

        Livewire::test(ListComments::class)
            ->assertCanSeeTableRecords(Comment::where('status', CommentStatus::Approved)->get())
            ->filterTable('status', CommentStatus::Pending->value)
            ->assertCanSeeTableRecords(Comment::where('status', CommentStatus::Pending)->get())
            ->assertCanNotSeeTableRecords(Comment::where('status', CommentStatus::Approved)->get());
    }

    public function test_can_approve_comment(): void
    {
        $this->actingAs($this->admin);
        $comment = Comment::factory()->pending()->create();

        Livewire::test(ListComments::class)
            ->callTableAction('approve', $comment->id);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'status' => CommentStatus::Approved->value,
        ]);
    }

    public function test_can_reject_comment(): void
    {
        $this->actingAs($this->admin);
        $comment = Comment::factory()->pending()->create();

        Livewire::test(ListComments::class)
            ->callTableAction('reject', $comment->id);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'status' => CommentStatus::Rejected->value,
        ]);
    }

    public function test_can_mark_comment_as_deleted(): void
    {
        $this->actingAs($this->admin);
        $comment = Comment::factory()->create();

        Livewire::test(ListComments::class)
            ->callTableAction('markAsDeleted', $comment->id);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'status' => CommentStatus::Deleted->value,
        ]);
    }

    public function test_can_reply_to_comment(): void
    {
        $this->actingAs($this->admin);
        $comment = Comment::factory()->approved()->create();

        Livewire::test(ListComments::class)
            ->callTableAction('reply', $comment->id, [
                'content' => '这是管理员回复',
            ]);

        $this->assertDatabaseHas('comments', [
            'content' => '这是管理员回复',
            'parent_id' => $comment->id,
            'article_id' => $comment->article_id,
            'user_id' => $this->admin->id,
            'status' => CommentStatus::Approved->value,
        ]);
    }

    public function test_can_filter_comments_by_article(): void
    {
        $this->actingAs($this->admin);
        $article = Article::factory()->create();
        $matching = Comment::factory()->count(2)->for($article)->create();
        Comment::factory()->count(3)->create();

        Livewire::test(ListComments::class)
            ->filterTable('article', $article->id)
            ->assertCanSeeTableRecords($matching)
            ->assertCanNotSeeTableRecords(Comment::whereNot('article_id', $article->id)->get());
    }

    public function test_can_create_comment_for_page(): void
    {
        $this->actingAs($this->admin);
        $page = Page::factory()->create();

        Livewire::test(CreateComment::class)
            ->fillForm([
                'content' => '页面评论测试',
                'status' => CommentStatus::Approved->value,
                'page_id' => $page->id,
                'user_id' => $this->admin->id,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('comments', [
            'content' => '页面评论测试',
            'status' => CommentStatus::Approved->value,
            'article_id' => null,
            'page_id' => $page->id,
            'user_id' => $this->admin->id,
        ]);
    }

    public function test_can_filter_comments_by_page(): void
    {
        $this->actingAs($this->admin);
        $page = Page::factory()->create();
        $matching = Comment::factory()->count(2)->state([
            'article_id' => null,
            'page_id' => $page->id,
        ])->create();
        Comment::factory()->count(3)->create();

        Livewire::test(ListComments::class)
            ->filterTable('page', $page->id)
            ->assertCanSeeTableRecords($matching)
            ->assertCanNotSeeTableRecords(Comment::whereNot('page_id', $page->id)->get());
    }

    public function test_can_filter_comments_by_user(): void
    {
        $this->actingAs($this->admin);
        $user = User::factory()->create();
        $matching = Comment::factory()->count(2)->for(Article::factory())->create(['user_id' => $user->id]);
        Comment::factory()->count(3)->create();

        Livewire::test(ListComments::class)
            ->filterTable('user', $user->id)
            ->assertCanSeeTableRecords($matching)
            ->assertCanNotSeeTableRecords(Comment::whereNot('user_id', $user->id)->get());
    }

    public function test_reply_to_page_comment_inherits_page_id(): void
    {
        $this->actingAs($this->admin);
        $page = Page::factory()->create();
        $comment = Comment::factory()->approved()->state([
            'article_id' => null,
            'page_id' => $page->id,
        ])->create();

        Livewire::test(ListComments::class)
            ->callTableAction('reply', $comment->id, [
                'content' => '页面评论回复',
            ]);

        $this->assertDatabaseHas('comments', [
            'content' => '页面评论回复',
            'parent_id' => $comment->id,
            'article_id' => null,
            'page_id' => $page->id,
            'user_id' => $this->admin->id,
            'status' => CommentStatus::Approved->value,
        ]);
    }

    public function test_can_filter_comments_by_author(): void
    {
        $this->actingAs($this->admin);
        $user = User::factory()->create(['name' => '唯一作者']);
        $matching = Comment::factory()->count(2)->for(Article::factory())->create(['user_id' => $user->id]);
        Comment::factory()->count(3)->create();

        Livewire::test(ListComments::class)
            ->filterTable('author', ['author' => '唯一作者'])
            ->assertCanSeeTableRecords($matching)
            ->assertCanNotSeeTableRecords(Comment::whereNot('user_id', $user->id)->get());
    }
}
