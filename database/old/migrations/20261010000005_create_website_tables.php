<?php

use think\migration\db\Column;
use think\migration\Migrator;

class CreateWebsiteTables extends Migrator
{
    /**
     * 网站域：轮播图/广告位 + 友情链接
     */
    public function change()
    {
        // 轮播图/广告表：按广告位标识分组展示
        $banners = $this->table('site_banners', ['comment' => '轮播图/广告表']);
        $banners->addColumn(Column::string('position', 50)->setNull(false)->setComment('广告位标识,如:home_top'))
            ->addColumn(Column::string('title', 100)->setNull(false)->setComment('标题'))
            ->addColumn(Column::string('image', 255)->setNull(false)->setComment('图片地址'))
            ->addColumn(Column::string('link', 255)->setNullable()->setComment('跳转链接'))
            ->addColumn(Column::string('description', 255)->setNullable()->setComment('描述'))
            ->addColumn(Column::integer('sort')->setNull(false)->setDefault(0)->setComment('排序'))
            ->addColumn(Column::tinyInteger('status')->setNull(false)->setDefault(1)->setComment('状态:0=禁用,1=启用'))
            ->addColumn(Column::dateTime('start_time')->setNullable()->setComment('投放开始时间'))
            ->addColumn(Column::dateTime('end_time')->setNullable()->setComment('投放结束时间'))
            ->addTimestamps()
            ->addIndex('position', ['name' => 'idx_banners_position'])
            ->create();

        // 友情链接表
        $links = $this->table('site_links', ['comment' => '友情链接表']);
        $links->addColumn(Column::string('name', 50)->setNull(false)->setComment('站点名称'))
            ->addColumn(Column::string('url', 255)->setNull(false)->setComment('链接地址'))
            ->addColumn(Column::string('logo', 255)->setNullable()->setComment('站点LOGO'))
            ->addColumn(Column::string('description', 255)->setNullable()->setComment('描述'))
            ->addColumn(Column::integer('sort')->setNull(false)->setDefault(0)->setComment('排序'))
            ->addColumn(Column::tinyInteger('status')->setNull(false)->setDefault(1)->setComment('状态:0=禁用,1=启用'))
            ->addTimestamps()
            ->create();
    }
}
