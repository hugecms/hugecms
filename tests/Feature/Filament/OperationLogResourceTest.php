<?php

namespace Tests\Feature\Filament;

use App\Enums\OperationLogType;
use App\Filament\Admin\Resources\OperationLogs\Pages\ListOperationLogs;
use App\Models\OperationLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class OperationLogResourceTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = $this->createSuperAdmin();
        $this->actingAs($this->admin);
    }

    public function test_can_render_operation_log_list(): void
    {
        Livewire::test(ListOperationLogs::class)
            ->assertSuccessful();
    }

    public function test_type_filter_works(): void
    {
        OperationLog::factory()->create(['type' => OperationLogType::Create]);
        OperationLog::factory()->create(['type' => OperationLogType::Update]);

        Livewire::test(ListOperationLogs::class)
            ->filterTable('type', OperationLogType::Create->value)
            ->assertCanSeeTableRecords(OperationLog::where('type', OperationLogType::Create)->get())
            ->assertCanNotSeeTableRecords(OperationLog::where('type', OperationLogType::Update)->get());
    }

    public function test_user_filter_works(): void
    {
        $user = User::factory()->create();
        $matching = OperationLog::factory()->create(['user_id' => $user->id]);
        $other = OperationLog::factory()->create();

        Livewire::test(ListOperationLogs::class)
            ->filterTable('user', $user->id)
            ->assertCanSeeTableRecords([$matching])
            ->assertCanNotSeeTableRecords([$other]);
    }

    public function test_created_at_filter_works(): void
    {
        $recent = OperationLog::factory()->create(['created_at' => now()]);
        $old = OperationLog::factory()->create(['created_at' => now()->subMonths(2)]);

        Livewire::test(ListOperationLogs::class)
            ->filterTable('created_at', [
                'from' => now()->subWeek()->toDateString(),
                'until' => now()->toDateString(),
            ])
            ->assertCanSeeTableRecords([$recent])
            ->assertCanNotSeeTableRecords([$old]);
    }

    public function test_resource_is_read_only(): void
    {
        Livewire::test(ListOperationLogs::class)
            ->assertTableHeaderActionsExistInOrder([])
            ->assertTableActionDoesNotExist('delete');
    }
}
