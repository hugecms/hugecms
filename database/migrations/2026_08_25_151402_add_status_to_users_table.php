<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('status')->default(1)->comment('状态：1 启用 0 禁用')->after('remember_token');
        });

        DB::table('users')->update(['status' => 1]);

        DB::table('menus')->where('name', '用户管理')->where('route', '')->update(['route' => 'admin.user']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        DB::table('menus')->where('name', '用户管理')->where('route', 'admin.user.index')->update(['route' => '']);
    }
};
