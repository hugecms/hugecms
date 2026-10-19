<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 系统域：系统配置、附件、数据字典、行政区划、审计日志（登录/操作）、敏感词
     */
    public function up(): void
    {
        // 系统配置表：后台"系统设置"页，按键值读写
        Schema::create('system_settings', function (Blueprint $table) {
            $table->comment('系统配置表');
            $table->id();
            $table->string('group', 30)->default('basic')->comment('配置分组:basic=基础,seo,upload');
            $table->string('key', 100)->comment('配置键,如:site_name');
            $table->string('title', 100)->comment('配置名称,用于后台表单展示');
            $table->text('value')->nullable()->comment('配置值');
            $table->string('type', 20)->default('text')->comment('值类型:text,number,select,radio');
            $table->timestamps();
            $table->unique('key', 'uk_settings_key');
            $table->index('group', 'idx_settings_group');
        });

        // 附件表：全站上传文件统一登记
        Schema::create('system_attachments', function (Blueprint $table) {
            $table->comment('附件表');
            $table->id();
            $table->string('storage', 20)->default('local')->comment('存储驱动:local,oss,qiniu');
            $table->string('file_path', 500)->comment('文件路径');
            $table->string('file_name', 255)->comment('原始文件名');
            $table->string('mime_type', 100)->comment('MIME类型');
            $table->unsignedInteger('file_size')->default(0)->comment('文件大小(字节)');
            $table->string('module', 30)->nullable()->comment('所属模块:article,product');
            $table->string('user_type', 20)->default('admin')->comment('上传账户类型:admin,user');
            $table->unsignedInteger('user_id')->nullable()->comment('上传人ID');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->index('module', 'idx_attachments_module');
        });

        // 字典类型表
        Schema::create('system_dicts', function (Blueprint $table) {
            $table->comment('字典类型表');
            $table->id();
            $table->string('type', 50)->comment('类型标识,如:gender');
            $table->string('name', 50)->comment('类型名称');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->timestamps();
            $table->unique('type', 'uk_dicts_type');
        });

        // 字典项表
        Schema::create('system_dict_items', function (Blueprint $table) {
            $table->comment('字典项表');
            $table->id();
            $table->unsignedInteger('dict_id')->comment('字典类型ID');
            $table->string('value', 100)->comment('字典值');
            $table->string('label', 100)->comment('字典标签');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->timestamps();
            $table->index('dict_id', 'idx_dict_items_dict_id');
        });

        // 行政区划表：省市区三级，收货地址/前台展示用，初始化时导入国标数据
        Schema::create('system_regions', function (Blueprint $table) {
            $table->comment('行政区划表');
            $table->id();
            $table->unsignedInteger('parent_id')->default(0)->comment('父级ID,0=省级');
            $table->string('name', 50)->comment('区域名称');
            $table->tinyInteger('level')->comment('层级:1=省,2=市,3=区县');
            $table->string('code', 20)->nullable()->comment('行政区划码');
            $table->integer('sort')->default(0)->comment('排序');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->index('parent_id', 'idx_regions_parent_id');
        });

        // 登录日志表：账户类型字段兼容前台用户与后台管理员
        Schema::create('system_login_logs', function (Blueprint $table) {
            $table->comment('登录日志表');
            $table->id();
            $table->string('user_type', 20)->comment('账户类型:admin=后台,user=前台');
            $table->unsignedInteger('user_id')->nullable()->comment('用户ID,账号不存在时为空');
            $table->string('username', 50)->comment('登录账号(尝试值快照)');
            $table->tinyInteger('status')->default(0)->comment('结果:0=失败,1=成功');
            $table->string('ip', 45)->comment('登录IP');
            $table->string('user_agent', 500)->nullable()->comment('User-Agent');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->index(['user_type', 'user_id'], 'idx_system_login_logs_user');
            $table->index('ip', 'idx_system_login_logs_ip');
            $table->index('create_time', 'idx_system_login_logs_create_time');
        });

        // 操作日志表
        Schema::create('system_operation_logs', function (Blueprint $table) {
            $table->comment('操作日志表');
            $table->id();
            $table->string('user_type', 20)->comment('账户类型:admin=后台,user=前台');
            $table->unsignedInteger('user_id')->nullable()->comment('用户ID');
            $table->string('username', 50)->comment('操作人账号快照');
            $table->string('title', 100)->comment('操作名称,如:删除用户');
            $table->string('controller', 50)->comment('控制器');
            $table->string('action', 50)->comment('方法');
            $table->string('method', 10)->comment('请求方式');
            $table->string('url', 255)->comment('请求地址');
            $table->text('params')->nullable()->comment('请求参数(JSON)');
            $table->tinyInteger('status')->default(1)->comment('结果:0=失败,1=成功');
            $table->unsignedInteger('cost_time')->default(0)->comment('耗时(毫秒)');
            $table->string('ip', 45)->comment('操作IP');
            $table->string('user_agent', 500)->nullable()->comment('User-Agent');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->index(['user_type', 'user_id'], 'idx_system_operation_logs_user');
            $table->index('create_time', 'idx_system_operation_logs_create_time');
        });

        // 敏感词表：评论/表单/昵称等内容审核过滤，replacement 为空则直接拦截
        Schema::create('system_sensitive_words', function (Blueprint $table) {
            $table->comment('敏感词表');
            $table->id();
            $table->string('word', 50)->comment('敏感词');
            $table->string('replacement', 50)->nullable()->comment('替换词,空=直接拦截');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->timestamps();
            $table->unique('word', 'uk_sensitive_words_word');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_sensitive_words');
        Schema::dropIfExists('system_operation_logs');
        Schema::dropIfExists('system_login_logs');
        Schema::dropIfExists('system_regions');
        Schema::dropIfExists('system_dict_items');
        Schema::dropIfExists('system_dicts');
        Schema::dropIfExists('system_attachments');
        Schema::dropIfExists('system_settings');
    }
};
