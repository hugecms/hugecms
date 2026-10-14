<?php

use think\migration\db\Column;
use think\migration\Migrator;

class CreateUsersTable extends Migrator
{
    /**
     * 用户账户表：仅存放登录凭证与账户状态，资料信息见 user_profiles 表
     */
    public function change()
    {
        $table = $this->table('users', ['comment' => '用户账户表']);
        $table->addColumn(Column::string('username', 50)->setComment('用户名'))
            ->addColumn(Column::string('email', 100)->setNullable()->setComment('邮箱'))
            ->addColumn(Column::string('phone', 20)->setNullable()->setComment('手机号'))
            ->addColumn(Column::string('password', 255)->setComment('密码哈希'))
            ->addColumn(Column::tinyInteger('status')->setDefault(1)->setComment('状态:0=禁用,1=正常'))
            ->addColumn(Column::dateTime('last_login_time')->setNullable()->setComment('最后登录时间'))
            ->addColumn(Column::string('last_login_ip', 45)->setNullable()->setComment('最后登录IP'))
            ->addTimestamps()
            ->addSoftDelete()
            ->addIndex('username', ['unique' => true, 'name' => 'uk_users_username'])
            ->addIndex('email', ['unique' => true, 'name' => 'uk_users_email'])
            ->addIndex('phone', ['unique' => true, 'name' => 'uk_users_phone'])
            ->create();
    }
}
