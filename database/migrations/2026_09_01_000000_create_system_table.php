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
        Schema::create('system_region', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('system_setting', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        //         region	行政区域表（省/市/区）	id, name, parent_id, level, zip_code
        // notification_template	消息模板（短信/邮件）	id, code, title, content, type
        // notification_log	消息发送日志	user_id, template_id, send_time, status, error_msg
        // setting	系统配置（键值对）	key, value, group, description
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_region');
        Schema::dropIfExists('system_setting');
    }
};
