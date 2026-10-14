<?php

use think\migration\db\Column;
use think\migration\Migrator;

class CreateFollowersTable extends Migrator
{
    /**
     * 关注关系表：user_id 被 follower_id 关注，联合主键防重复关注
     */
    public function change()
    {
        $table = $this->table('followers', ['id' => false, 'primary_key' => ['user_id', 'follower_id'], 'comment' => '关注关系表']);
        $table->addColumn(Column::unsignedInteger('user_id')->setComment('被关注用户ID'))
            ->addColumn(Column::unsignedInteger('follower_id')->setComment('关注者用户ID'))
            ->addTimestamps('create_time', null)
            ->addIndex('follower_id', ['name' => 'idx_followers_follower_id'])
            ->create();
    }
}
