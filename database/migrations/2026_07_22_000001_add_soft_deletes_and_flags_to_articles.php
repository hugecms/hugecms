<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->softDeletes();
            $table->boolean('is_pinned')->default(false)->after('status');
            $table->boolean('is_recommended')->default(false)->after('is_pinned');
            $table->dropColumn('cover_image');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['is_pinned', 'is_recommended']);
            $table->string('cover_image')->nullable()->after('content');
        });
    }
};
