<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->comment('用户-角色关联表');
            $table->foreignId('role_id')->constrained()->cascadeOnDelete()->comment('角色 ID');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->comment('用户 ID');
            $table->primary(['role_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_user');
    }
};
