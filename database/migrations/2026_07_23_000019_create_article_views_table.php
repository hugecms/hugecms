<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
            $table->string('visitor_hash', 64)->index();
            $table->ipAddress('ip_address')->nullable();
            $table->timestamp('viewed_at');
            $table->timestamps();

            $table->index(['article_id', 'viewed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_views');
    }
};
