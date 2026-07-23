<?php

namespace Tests\Feature;

use App\Enums\ContentStatus;
use App\Enums\OperationLogType;
use App\Filament\Admin\Pages\RecycleBin;
use App\Models\Article;
use App\Models\OperationLog;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class OperationLogTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = $this->createSuperAdmin();
        $this->actingAs($this->admin);
    }

    public function test_create_article_logs_create_operation(): void
    {
        $article = Article::factory()->create();

        $this->assertDatabaseHas('operation_logs', [
            'type' => OperationLogType::Create->value,
            'object_type' => 'article',
            'object_id' => $article->id,
            'user_id' => $this->admin->id,
        ]);
    }

    public function test_update_article_logs_update_operation_with_summary(): void
    {
        $article = Article::factory()->create();

        $article->update(['title' => '更新后的标题']);

        $this->assertDatabaseHas('operation_logs', [
            'type' => OperationLogType::Update->value,
            'object_type' => 'article',
            'object_id' => $article->id,
            'user_id' => $this->admin->id,
        ]);

        $log = OperationLog::where([
            'type' => OperationLogType::Update,
            'object_type' => 'article',
            'object_id' => $article->id,
        ])->first();

        $this->assertStringContainsString('title', $log->summary);
    }

    public function test_publish_article_logs_publish_operation(): void
    {
        $article = Article::factory()->create(['status' => ContentStatus::Draft]);

        $article->update(['status' => ContentStatus::Published]);

        $this->assertDatabaseHas('operation_logs', [
            'type' => OperationLogType::Publish->value,
            'object_type' => 'article',
            'object_id' => $article->id,
        ]);
    }

    public function test_soft_delete_logs_delete_operation(): void
    {
        $article = Article::factory()->create();

        $article->delete();

        $this->assertDatabaseHas('operation_logs', [
            'type' => OperationLogType::Delete->value,
            'object_type' => 'article',
            'object_id' => $article->id,
        ]);
    }

    public function test_restore_logs_restore_operation(): void
    {
        $article = Article::factory()->create(['deleted_at' => now()]);

        $article->restore();

        $this->assertDatabaseHas('operation_logs', [
            'type' => OperationLogType::Restore->value,
            'object_type' => 'article',
            'object_id' => $article->id,
        ]);
    }

    public function test_force_delete_logs_force_delete_operation(): void
    {
        $article = Article::factory()->create(['deleted_at' => now()]);

        $article->forceDelete();

        $this->assertDatabaseHas('operation_logs', [
            'type' => OperationLogType::ForceDelete->value,
            'object_type' => 'article',
            'object_id' => $article->id,
        ]);
    }

    public function test_recycle_bin_restore_creates_log(): void
    {
        $article = Article::factory()->create(['deleted_at' => now()]);

        Livewire::test(RecycleBin::class)
            ->callTableAction('restore', $article->id);

        $this->assertDatabaseHas('operation_logs', [
            'type' => OperationLogType::Restore->value,
            'object_type' => 'article',
            'object_id' => $article->id,
        ]);
    }

    public function test_recycle_bin_force_delete_creates_log(): void
    {
        $article = Article::factory()->create(['deleted_at' => now()]);

        Livewire::test(RecycleBin::class)
            ->callTableAction('forceDelete', $article->id);

        $this->assertDatabaseHas('operation_logs', [
            'type' => OperationLogType::ForceDelete->value,
            'object_type' => 'article',
            'object_id' => $article->id,
        ]);
    }

    public function test_login_event_creates_log(): void
    {
        Event::dispatch(new Login('web', $this->admin, false));

        $this->assertDatabaseHas('operation_logs', [
            'type' => OperationLogType::Login->value,
            'object_type' => 'system',
            'user_id' => $this->admin->id,
        ]);
    }

    public function test_logout_event_creates_log(): void
    {
        Event::dispatch(new Logout('web', $this->admin));

        $this->assertDatabaseHas('operation_logs', [
            'type' => OperationLogType::Logout->value,
            'object_type' => 'system',
            'user_id' => $this->admin->id,
        ]);
    }

    public function test_auditor_can_view_operation_logs_but_editor_cannot(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $this->seed([PermissionSeeder::class, RoleSeeder::class]);

        $auditor = User::factory()->create();
        $auditor->assignRole('auditor');

        $editor = User::factory()->create();
        $editor->assignRole('editor');

        $this->assertTrue($auditor->can('operation_log.view_any'));
        $this->assertFalse($editor->can('operation_log.view_any'));
    }
}
