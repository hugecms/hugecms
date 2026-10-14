<?php

use think\migration\db\Column;
use think\migration\Migrator;

class CreateFormTables extends Migrator
{
    /**
     * 自定义表单域：表单定义、字段定义、提交数据（报名表、留言板等场景）
     */
    public function change()
    {
        // 表单定义表
        $forms = $this->table('forms', ['comment' => '自定义表单表']);
        $forms->addColumn(Column::string('name', 50)->setNull(false)->setComment('表单名称,如:活动报名'))
            ->addColumn(Column::string('description', 255)->setNullable()->setComment('表单描述'))
            ->addColumn(Column::string('success_message', 255)->setNullable()->setComment('提交成功提示语'))
            ->addColumn(Column::dateTime('start_time')->setNullable()->setComment('可提交开始时间,空=不限'))
            ->addColumn(Column::dateTime('end_time')->setNullable()->setComment('可提交结束时间,空=不限'))
            ->addColumn(Column::tinyInteger('need_login')->setNull(false)->setDefault(0)->setComment('需要登录:0=否,1=是'))
            ->addColumn(Column::tinyInteger('max_submits')->setNull(false)->setDefault(0)->setComment('每人限提次数,0=不限'))
            ->addColumn(Column::tinyInteger('status')->setNull(false)->setDefault(1)->setComment('状态:0=停用,1=启用'))
            ->addTimestamps()
            ->addSoftDelete()
            ->create();

        // 表单字段表：渲染与校验依据
        $fields = $this->table('form_fields', ['comment' => '表单字段表']);
        $fields->addColumn(Column::unsignedInteger('form_id')->setNull(false)->setComment('表单ID'))
            ->addColumn(Column::string('name', 50)->setNull(false)->setComment('字段名称,如:联系电话'))
            ->addColumn(Column::string('field_name', 50)->setNull(false)->setComment('字段标识,英文,提交数据的键'))
            ->addColumn(Column::string('type', 20)->setNull(false)->setComment('类型:text,textarea,select,radio,checkbox,number,date,image,file'))
            ->addColumn(Column::string('options', 500)->setNullable()->setComment('选项(JSON),select/radio/checkbox用'))
            ->addColumn(Column::string('default_value', 255)->setNullable()->setComment('默认值'))
            ->addColumn(Column::tinyInteger('is_required')->setNull(false)->setDefault(0)->setComment('必填:0=否,1=是'))
            ->addColumn(Column::string('pattern', 255)->setNullable()->setComment('正则验证规则'))
            ->addColumn(Column::integer('sort')->setNull(false)->setDefault(0)->setComment('排序'))
            ->addColumn(Column::tinyInteger('status')->setNull(false)->setDefault(1)->setComment('状态:0=隐藏,1=显示'))
            ->addTimestamps()
            ->addIndex(['form_id', 'field_name'], ['unique' => true, 'name' => 'uk_form_fields_form_field'])
            ->create();

        // 提交数据表：data 为 JSON，键为字段标识；导出/检索用 JSON_EXTRACT
        $submissions = $this->table('form_submissions', ['comment' => '表单提交数据表']);
        $submissions->addColumn(Column::unsignedInteger('form_id')->setNull(false)->setComment('表单ID'))
            ->addColumn(Column::unsignedInteger('user_id')->setNullable()->setComment('提交用户ID,游客为空'))
            ->addColumn(Column::json('data')->setNull(false)->setComment('提交数据(JSON,{字段标识:值})'))
            ->addColumn(Column::tinyInteger('status')->setNull(false)->setDefault(0)->setComment('处理状态:0=未处理,1=已处理'))
            ->addColumn(Column::string('ip', 45)->setNullable()->setComment('提交IP'))
            ->addColumn(Column::string('user_agent', 500)->setNullable()->setComment('User-Agent'))
            ->addTimestamps('create_time', null)
            ->addIndex(['form_id', 'create_time'], ['name' => 'idx_form_submissions_form_time'])
            ->addIndex('user_id', ['name' => 'idx_form_submissions_user_id'])
            ->create();
    }
}
