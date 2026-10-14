<?php

use think\migration\db\Column;
use think\migration\Migrator;

class CreateAuditLogsTable extends Migrator
{
    /**
     * 系统域审计日志：登录日志 + 操作日志，账户类型字段兼容前台用户与后台管理员
     */
    public function change()
    {
        // 登录日志表
        $loginLogs = $this->table('system_login_logs', ['comment' => '登录日志表']);
        $loginLogs->addColumn(Column::string('user_type', 20)->setNull(false)->setComment('账户类型:admin=后台,user=前台'))
            ->addColumn(Column::unsignedInteger('user_id')->setNullable()->setComment('用户ID,账号不存在时为空'))
            ->addColumn(Column::string('username', 50)->setNull(false)->setComment('登录账号(尝试值快照)'))
            ->addColumn(Column::tinyInteger('status')->setNull(false)->setDefault(0)->setComment('结果:0=失败,1=成功'))
            ->addColumn(Column::string('ip', 45)->setNull(false)->setComment('登录IP'))
            ->addColumn(Column::string('user_agent', 500)->setNullable()->setComment('User-Agent'))
            ->addTimestamps('create_time', null)
            ->addIndex(['user_type', 'user_id'], ['name' => 'idx_system_login_logs_user'])
            ->addIndex('ip', ['name' => 'idx_system_login_logs_ip'])
            ->addIndex('create_time', ['name' => 'idx_system_login_logs_create_time'])
            ->create();

        // 操作日志表
        $operationLogs = $this->table('system_operation_logs', ['comment' => '操作日志表']);
        $operationLogs->addColumn(Column::string('user_type', 20)->setNull(false)->setComment('账户类型:admin=后台,user=前台'))
            ->addColumn(Column::unsignedInteger('user_id')->setNullable()->setComment('用户ID'))
            ->addColumn(Column::string('username', 50)->setNull(false)->setComment('操作人账号快照'))
            ->addColumn(Column::string('title', 100)->setNull(false)->setComment('操作名称,如:删除用户'))
            ->addColumn(Column::string('controller', 50)->setNull(false)->setComment('控制器'))
            ->addColumn(Column::string('action', 50)->setNull(false)->setComment('方法'))
            ->addColumn(Column::string('method', 10)->setNull(false)->setComment('请求方式'))
            ->addColumn(Column::string('url', 255)->setNull(false)->setComment('请求地址'))
            ->addColumn(Column::text('params')->setNullable()->setComment('请求参数(JSON)'))
            ->addColumn(Column::tinyInteger('status')->setNull(false)->setDefault(1)->setComment('结果:0=失败,1=成功'))
            ->addColumn(Column::unsignedInteger('cost_time')->setNull(false)->setDefault(0)->setComment('耗时(毫秒)'))
            ->addColumn(Column::string('ip', 45)->setNull(false)->setComment('操作IP'))
            ->addColumn(Column::string('user_agent', 500)->setNullable()->setComment('User-Agent'))
            ->addTimestamps('create_time', null)
            ->addIndex(['user_type', 'user_id'], ['name' => 'idx_system_operation_logs_user'])
            ->addIndex('create_time', ['name' => 'idx_system_operation_logs_create_time'])
            ->create();
    }
}
