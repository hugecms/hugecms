<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            $table->string('title');
            $table->string('url')->nullable();
            $table->string('type')->default('custom');
            $table->string('target')->default('_self');
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->nullableMorphs('linkable');

            $table->unsignedBigInteger('_lft')->default(0);
            $table->unsignedBigInteger('_rgt')->default(0);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->index(['_lft', '_rgt', 'parent_id']);
            $table->foreign('parent_id')->references('id')->on('menu_items')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropIndex(['_lft', '_rgt', 'parent_id']);
            $table->dropColumn(['_lft', '_rgt', 'parent_id']);
        });

        Schema::dropIfExists('menu_items');
    }
};
