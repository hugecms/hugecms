<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recycle_bin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('deleted_by')->default(0)->comment('删除人用户ID');
            $table->string('target_type', 30)->comment('原对象类型：content/term/attachment/user/form_submission/comment');
            $table->string('target_id', 64)->comment('原对象ID');
            $table->json('original_data')->comment('删除前的全量数据快照（JSON）');
            $table->json('restore_data')->nullable()->comment('恢复时所需的数据映射（如恢复时需新建ID）');
            $table->unsignedInteger('retention_days')->default(30)->comment('保留天数（超时由 Scheduler 物理清除）');
            $table->timestamp('created_at')->useCurrent()->comment('删除时间');
            $table->dateTime('expire_at')->virtualAs('DATE_ADD(created_at, INTERVAL retention_days DAY)')->comment('过期时间（虚拟生成列）');
            $table->index(['target_type', 'target_id']);
            $table->index('expire_at');
            $table->comment('回收站表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recycle_bin');
    }
};
