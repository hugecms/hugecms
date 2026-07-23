<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Kalnoy\Nestedset\NestedSet;

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
            NestedSet::columns($table);
            $table->foreign('parent_id')->references('id')->on('menu_items')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            NestedSet::dropColumns($table);
        });

        Schema::dropIfExists('menu_items');
    }
};
