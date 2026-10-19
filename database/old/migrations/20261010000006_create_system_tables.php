<?php

use think\migration\db\Column;
use think\migration\Migrator;

class CreateSystemTables extends Migrator
{
    /**
     * 系统域：系统配置、附件、数据字典、行政区划
     */
    public function change()
    {
        // 系统配置表：后台"系统设置"页，按键值读写
        $settings = $this->table('system_settings', ['comment' => '系统配置表']);
        $settings->addColumn(Column::string('group', 30)->setNull(false)->setDefault('basic')->setComment('配置分组:basic=基础,seo,upload'))
            ->addColumn(Column::string('key', 100)->setNull(false)->setComment('配置键,如:site_name'))
            ->addColumn(Column::string('title', 100)->setNull(false)->setComment('配置名称,用于后台表单展示'))
            ->addColumn(Column::text('value')->setNullable()->setComment('配置值'))
            ->addColumn(Column::string('type', 20)->setNull(false)->setDefault('text')->setComment('值类型:text,number,select,radio'))
            ->addTimestamps()
            ->addIndex('key', ['unique' => true, 'name' => 'uk_settings_key'])
            ->addIndex('group', ['name' => 'idx_settings_group'])
            ->create();

        // 附件表：全站上传文件统一登记
        $attachments = $this->table('system_attachments', ['comment' => '附件表']);
        $attachments->addColumn(Column::string('storage', 20)->setNull(false)->setDefault('local')->setComment('存储驱动:local,oss,qiniu'))
            ->addColumn(Column::string('file_path', 500)->setNull(false)->setComment('文件路径'))
            ->addColumn(Column::string('file_name', 255)->setNull(false)->setComment('原始文件名'))
            ->addColumn(Column::string('mime_type', 100)->setNull(false)->setComment('MIME类型'))
            ->addColumn(Column::unsignedInteger('file_size')->setNull(false)->setDefault(0)->setComment('文件大小(字节)'))
            ->addColumn(Column::string('module', 30)->setNullable()->setComment('所属模块:article,product'))
            ->addColumn(Column::string('user_type', 20)->setNull(false)->setDefault('admin')->setComment('上传账户类型:admin,user'))
            ->addColumn(Column::unsignedInteger('user_id')->setNullable()->setComment('上传人ID'))
            ->addTimestamps('create_time', null)
            ->addIndex('module', ['name' => 'idx_attachments_module'])
            ->create();

        // 字典类型表
        $dicts = $this->table('system_dicts', ['comment' => '字典类型表']);
        $dicts->addColumn(Column::string('type', 50)->setNull(false)->setComment('类型标识,如:gender'))
            ->addColumn(Column::string('name', 50)->setNull(false)->setComment('类型名称'))
            ->addColumn(Column::tinyInteger('status')->setNull(false)->setDefault(1)->setComment('状态:0=禁用,1=启用'))
            ->addTimestamps()
            ->addIndex('type', ['unique' => true, 'name' => 'uk_dicts_type'])
            ->create();

        // 字典项表
        $dictItems = $this->table('system_dict_items', ['comment' => '字典项表']);
        $dictItems->addColumn(Column::unsignedInteger('dict_id')->setNull(false)->setComment('字典类型ID'))
            ->addColumn(Column::string('value', 100)->setNull(false)->setComment('字典值'))
            ->addColumn(Column::string('label', 100)->setNull(false)->setComment('字典标签'))
            ->addColumn(Column::integer('sort')->setNull(false)->setDefault(0)->setComment('排序'))
            ->addColumn(Column::tinyInteger('status')->setNull(false)->setDefault(1)->setComment('状态:0=禁用,1=启用'))
            ->addTimestamps()
            ->addIndex('dict_id', ['name' => 'idx_dict_items_dict_id'])
            ->create();

        // 行政区划表：省市区三级，收货地址/前台展示用，初始化时导入国标数据
        $regions = $this->table('system_regions', ['comment' => '行政区划表']);
        $regions->addColumn(Column::unsignedInteger('parent_id')->setNull(false)->setDefault(0)->setComment('父级ID,0=省级'))
            ->addColumn(Column::string('name', 50)->setNull(false)->setComment('区域名称'))
            ->addColumn(Column::tinyInteger('level')->setNull(false)->setComment('层级:1=省,2=市,3=区县'))
            ->addColumn(Column::string('code', 20)->setNullable()->setComment('行政区划码'))
            ->addColumn(Column::integer('sort')->setNull(false)->setDefault(0)->setComment('排序'))
            ->addTimestamps('create_time', null)
            ->addIndex('parent_id', ['name' => 'idx_regions_parent_id'])
            ->create();
    }
}
