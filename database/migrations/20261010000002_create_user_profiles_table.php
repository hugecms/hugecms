<?php

use think\migration\db\Column;
use think\migration\Migrator;

class CreateUserProfilesTable extends Migrator
{
    /**
     * 用户资料表：与 users 表一对一（user_id 主键），存放展示类信息
     */
    public function change()
    {
        $table = $this->table('user_profiles', ['id' => false, 'primary_key' => ['user_id'], 'comment' => '用户资料表']);
        $table->addColumn(Column::unsignedInteger('user_id')->setComment('用户ID'))
            ->addColumn(Column::string('nickname', 50)->setComment('昵称'))
            ->addColumn(Column::string('avatar', 255)->setNullable()->setComment('头像'))
            ->addColumn(Column::tinyInteger('gender')->setDefault(0)->setComment('性别:0=未知,1=男,2=女'))
            ->addColumn(Column::date('birthday')->setNullable()->setComment('生日'))
            ->addColumn(Column::string('bio', 255)->setNullable()->setComment('个人简介'))
            ->addColumn(Column::string('location', 100)->setNullable()->setComment('所在地'))
            ->addTimestamps()
            ->create();
    }
}
