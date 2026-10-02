<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->comment('站点配置表');
            $table->id()->comment('配置ID');
            $table->string('group', 32)->index()->comment('配置分组（site/seo/reading/comment）');
            $table->string('key', 64)->unique()->comment('配置键（如 site.name、seo.title_suffix）');
            $table->text('value')->comment('配置值（JSON）');
            $table->string('remark')->nullable()->comment('配置说明');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
