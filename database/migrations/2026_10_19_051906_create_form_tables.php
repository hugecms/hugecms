<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 自定义表单域：表单定义、字段定义、提交数据（报名表、留言板等场景）
     */
    public function up(): void
    {
        // 表单定义表
        Schema::create('forms', function (Blueprint $table) {
            $table->comment('自定义表单表');
            $table->id();
            $table->string('name', 50)->comment('表单名称,如:活动报名');
            $table->string('description', 255)->nullable()->comment('表单描述');
            $table->string('success_message', 255)->nullable()->comment('提交成功提示语');
            $table->dateTime('start_time')->nullable()->comment('可提交开始时间,空=不限');
            $table->dateTime('end_time')->nullable()->comment('可提交结束时间,空=不限');
            $table->tinyInteger('need_login')->default(0)->comment('需要登录:0=否,1=是');
            $table->tinyInteger('max_submits')->default(0)->comment('每人限提次数,0=不限');
            $table->tinyInteger('status')->default(1)->comment('状态:0=停用,1=启用');
            $table->timestamps();
            $table->softDeletes();
        });

        // 表单字段表：渲染与校验依据
        Schema::create('form_fields', function (Blueprint $table) {
            $table->comment('表单字段表');
            $table->id();
            $table->unsignedInteger('form_id')->comment('表单ID');
            $table->string('name', 50)->comment('字段名称,如:联系电话');
            $table->string('field_name', 50)->comment('字段标识,英文,提交数据的键');
            $table->string('type', 20)->comment('类型:text=文本,textarea=多行文本,select=下拉单选,radio=单选,checkbox=多选,number=数字,date=日期,image=图片,file=文件');
            $table->string('options', 500)->nullable()->comment('选项(JSON),select/radio/checkbox用');
            $table->string('default_value', 255)->nullable()->comment('默认值');
            $table->tinyInteger('is_required')->default(0)->comment('必填:0=否,1=是');
            $table->string('pattern', 255)->nullable()->comment('正则验证规则');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态:0=隐藏,1=显示');
            $table->timestamps();
            $table->unique(['form_id', 'field_name'], 'uk_form_fields_form_field');
        });

        // 提交数据表：data 为 JSON，键为字段标识；导出/检索用 JSON_EXTRACT
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->comment('表单提交数据表');
            $table->id();
            $table->unsignedInteger('form_id')->comment('表单ID');
            $table->unsignedInteger('user_id')->nullable()->comment('提交用户ID,游客为空');
            $table->json('data')->comment('提交数据(JSON,{字段标识:值})');
            $table->tinyInteger('status')->default(0)->comment('处理状态:0=未处理,1=已处理');
            $table->string('ip', 45)->nullable()->comment('提交IP');
            $table->string('user_agent', 500)->nullable()->comment('User-Agent');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->index(['form_id', 'create_time'], 'idx_form_submissions_form_time');
            $table->index('user_id', 'idx_form_submissions_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_submissions');
        Schema::dropIfExists('form_fields');
        Schema::dropIfExists('forms');
    }
};
