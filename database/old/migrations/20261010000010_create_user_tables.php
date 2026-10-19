<?php

use think\migration\db\Column;
use think\migration\Migrator;

class CreateUserTables extends Migrator
{
    /**
     * 用户域补充表：收货地址、收藏、短信验证码、通知、社交登录绑定
     */
    public function change()
    {
        // 收货地址表
        $userAddresses = $this->table('user_addresses', ['comment' => '用户收货地址表']);
        $userAddresses->addColumn(Column::unsignedInteger('user_id')->setNull(false)->setComment('用户ID'))
            ->addColumn(Column::string('receiver_name', 50)->setNull(false)->setComment('收货人'))
            ->addColumn(Column::string('receiver_phone', 20)->setNull(false)->setComment('收货电话'))
            ->addColumn(Column::string('receiver_region', 100)->setNull(false)->setComment('省市区'))
            ->addColumn(Column::string('receiver_address', 255)->setNull(false)->setComment('详细地址'))
            ->addColumn(Column::tinyInteger('is_default')->setNull(false)->setDefault(0)->setComment('默认地址:0=否,1=是'))
            ->addTimestamps()
            ->addSoftDelete()
            ->addIndex('user_id', ['name' => 'idx_user_addresses_user_id'])
            ->create();

        // 收藏表：多态目标，文章/商品共用，与 user_followers 同类互动关系
        $favorites = $this->table('user_favorites', ['comment' => '用户收藏表']);
        $favorites->addColumn(Column::unsignedInteger('user_id')->setNull(false)->setComment('用户ID'))
            ->addColumn(Column::string('target_type', 20)->setNull(false)->setComment('目标类型:article,product'))
            ->addColumn(Column::unsignedInteger('target_id')->setNull(false)->setComment('目标ID'))
            ->addTimestamps('create_time', null)
            ->addIndex(['user_id', 'target_type', 'target_id'], ['unique' => true, 'name' => 'uk_user_favorites_user_target'])
            ->addIndex(['target_type', 'target_id'], ['name' => 'idx_user_favorites_target'])
            ->create();

        // 短信验证码表：注册/登录/找回密码，used_at+expire_at 控制一次性与时效
        $smsCodes = $this->table('user_sms_codes', ['comment' => '短信验证码表']);
        $smsCodes->addColumn(Column::string('phone', 20)->setNull(false)->setComment('手机号'))
            ->addColumn(Column::string('code', 10)->setNull(false)->setComment('验证码'))
            ->addColumn(Column::string('scene', 30)->setNull(false)->setComment('场景:register,login,reset_password'))
            ->addColumn(Column::dateTime('expire_at')->setNull(false)->setComment('过期时间'))
            ->addColumn(Column::dateTime('used_at')->setNullable()->setComment('使用时间'))
            ->addColumn(Column::string('ip', 45)->setNullable()->setComment('发送IP,用于防刷'))
            ->addTimestamps('create_time', null)
            ->addIndex(['phone', 'scene'], ['name' => 'idx_user_sms_codes_phone_scene'])
            ->addIndex('ip', ['name' => 'idx_user_sms_codes_ip'])
            ->addIndex('create_time', ['name' => 'idx_user_sms_codes_create_time'])
            ->create();

        // 通知表：订单状态变更、评论回复等站内触达，read_at 为空即未读
        $notifications = $this->table('user_notifications', ['comment' => '用户通知表']);
        $notifications->addColumn(Column::unsignedInteger('user_id')->setNull(false)->setComment('接收用户ID'))
            ->addColumn(Column::string('type', 30)->setNull(false)->setComment('类型:order,comment,system'))
            ->addColumn(Column::string('title', 100)->setNull(false)->setComment('通知标题'))
            ->addColumn(Column::string('content', 500)->setNull(false)->setComment('通知内容'))
            ->addColumn(Column::string('params', 500)->setNullable()->setComment('跳转参数(JSON)'))
            ->addColumn(Column::dateTime('read_at')->setNullable()->setComment('已读时间,空=未读'))
            ->addTimestamps('create_time', null)
            ->addIndex(['user_id', 'read_at'], ['name' => 'idx_user_notifications_user_read'])
            ->create();

        // 社交登录绑定表：一个用户可绑多个平台，未绑定用户首次授权时静默注册
        $socials = $this->table('user_socials', ['comment' => '社交登录绑定表']);
        $socials->addColumn(Column::unsignedInteger('user_id')->setNull(false)->setComment('用户ID'))
            ->addColumn(Column::string('platform', 20)->setNull(false)->setComment('平台:wechat,qq,weibo,github'))
            ->addColumn(Column::string('open_id', 64)->setNull(false)->setComment('第三方OpenID'))
            ->addColumn(Column::string('union_id', 64)->setNullable()->setComment('第三方UnionID,如微信多应用互通'))
            ->addColumn(Column::string('nickname', 50)->setNullable()->setComment('第三方昵称快照'))
            ->addColumn(Column::string('avatar', 255)->setNullable()->setComment('第三方头像快照'))
            ->addTimestamps()
            ->addIndex(['platform', 'open_id'], ['unique' => true, 'name' => 'uk_user_socials_platform_openid'])
            ->addIndex('user_id', ['name' => 'idx_user_socials_user_id'])
            ->create();
    }
}
