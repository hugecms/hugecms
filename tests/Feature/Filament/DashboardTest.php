<?php

namespace Tests\Feature\Filament;

use App\Enums\ContentStatus;
use App\Enums\OperationLogType;
use App\Filament\Admin\Widgets\DashboardStatsOverview;
use App\Filament\Admin\Widgets\QuickActionsWidget;
use App\Filament\Admin\Widgets\RecentActivityWidget;
use App\Models\Article;
use App\Models\Comment;
use App\Models\OperationLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = $this->createSuperAdmin();
        $this->actingAs($this->admin);
    }

    public function test_stats_overview_widget_renders(): void
    {
        Article::factory()->count(3)->create();
        User::factory()->count(2)->create();

        $article = Article::factory()->create();
        Comment::factory()->count(4)->pending()->create([
            'article_id' => $article->id,
            'user_id' => null,
        ]);

        $expectedArticles = Article::count();
        $expectedUsers = User::count();

        Livewire::test(DashboardStatsOverview::class)
            ->assertSuccessful()
            ->assertSeeText('文章总数')
            ->assertSeeTextInOrder(['文章总数', (string) $expectedArticles])
            ->assertSeeTextInOrder(['用户总数', (string) $expectedUsers])
            ->assertSeeText('今日访问量')
            ->assertSeeTextInOrder(['今日访问量', '0'])
            ->assertSeeText('UV:')
            ->assertSeeText('今日新增用户')
            ->assertSeeTextInOrder(['待审核内容', '4']);
    }

    public function test_quick_actions_widget_renders(): void
    {
        Livewire::test(QuickActionsWidget::class)
            ->assertSuccessful()
            ->assertSeeText('新增文章')
            ->assertSeeText('新增页面')
            ->assertSeeText('查看待办');
    }

    public function test_recent_activity_widget_shows_login_events(): void
    {
        OperationLog::factory()->create([
            'type' => OperationLogType::Login,
            'user_id' => $this->admin->id,
            'object_type' => 'system',
        ]);

        Livewire::test(RecentActivityWidget::class)
            ->assertSuccessful()
            ->assertSeeText('登录')
            ->assertSeeText($this->admin->name);
    }

    public function test_recent_activity_widget_shows_published_articles(): void
    {
        $article = Article::factory()->create([
            'status' => ContentStatus::Published,
            'published_at' => now(),
        ]);

        Livewire::test(RecentActivityWidget::class)
            ->assertSuccessful()
            ->assertSeeText('发布')
            ->assertSeeText($article->title);
    }

    public function test_recent_activity_widget_shows_operation_logs(): void
    {
        OperationLog::factory()->create([
            'type' => OperationLogType::Create,
            'summary' => 'article 测试标题',
        ]);

        Livewire::test(RecentActivityWidget::class)
            ->assertSuccessful()
            ->assertSeeText('操作日志')
            ->assertSeeText('测试标题');
    }
}
