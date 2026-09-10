<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('short_link_clicks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('short_link_id')->comment('短链接ID');
            $table->string('click_ip', 45)->default('')->comment('点击者IP');
            $table->string('user_agent')->default('')->comment('浏览器UA');
            $table->string('referer', 500)->default('')->comment('来源页');
            $table->timestamp('created_at')->useCurrent();
            $table->index('short_link_id');
            $table->index('created_at');
            $table->comment('短链接点击明细表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('short_link_clicks');
    }
};
