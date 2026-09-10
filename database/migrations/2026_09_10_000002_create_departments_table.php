<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(0)->comment('父级部门ID（0表示顶级）');
            $table->string('name', 100)->comment('部门名称（如：产品中心、技术研发部）');
            $table->string('code', 50)->comment('部门编码（如：PD、RD）')->unique();
            $table->unsignedBigInteger('leader_id')->nullable()->comment('部门负责人ID');
            $table->string('description')->default('')->comment('部门描述');
            $table->integer('sort')->default(0)->comment('排序权重');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态：0停用，1启用');
            $table->timestamps();
            $table->index('parent_id');
            $table->foreign('leader_id')->references('id')->on('users')->nullOnDelete()->cascadeOnUpdate();
            $table->comment('部门表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
