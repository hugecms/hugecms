<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_meta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('关联用户ID');
            $table->string('meta_key', 100)->default('')->comment('元数据键名');
            $table->longText('meta_value')->nullable()->comment('元数据值（JSON或序列化数据）');
            $table->timestamps();
            $table->unique(['user_id', 'meta_key']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->comment('用户元数据表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_meta');
    }
};
