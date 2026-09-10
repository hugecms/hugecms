<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_departments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->unsignedBigInteger('dept_id')->comment('部门ID');
            $table->unsignedTinyInteger('is_primary')->default(0)->comment('是否主属部门：1是（数据范围默认值），0否');
            $table->string('position', 50)->default('member')->comment('岗位：member专员/supervisor主管/manager经理/director总监');
            $table->date('entry_date')->nullable()->comment('入职日期');
            $table->timestamps();
            $table->unique(['user_id', 'dept_id']);
            $table->index(['dept_id', 'user_id'], 'idx_dept_user');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('dept_id')->references('id')->on('departments')->cascadeOnDelete()->cascadeOnUpdate();
            $table->comment('用户部门关联表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_departments');
    }
};
