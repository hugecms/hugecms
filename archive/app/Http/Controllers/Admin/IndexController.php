<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * 管理面板入口：布局首页 + 管理菜单数据源。
 */
class IndexController extends Controller
{
    /**
     * 管理菜单结构（与权限树模块对应），
     * 供 layouts/partials/sidebar 渲染；接入权限后可按用户权限过滤。
     *
     * @return array<int, array{title: string, items: array<int, array{title: string, route: string, active: string}>}}>
     */
    public static function menu(): array
    {
        return [
            ['title' => '概览', 'items' => [
                ['title' => '管理面板', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard'],
                ['title' => '数据统计', 'route' => 'admin.statistics.index', 'active' => 'admin.statistics.*'],
            ]],
            ['title' => '内容', 'items' => [
                ['title' => '内容管理', 'route' => 'admin.contents.index', 'active' => 'admin.contents.*'],
                ['title' => '内容模型', 'route' => 'admin.content-models.index', 'active' => 'admin.content-models.*'],
                ['title' => '分类标签', 'route' => 'admin.taxonomies.index', 'active' => 'admin.taxonomies.*'],
                ['title' => '评论管理', 'route' => 'admin.comments.index', 'active' => 'admin.comments.*'],
                ['title' => '回收站', 'route' => 'admin.recycle.index', 'active' => 'admin.recycle.*'],
            ]],
            ['title' => '媒体', 'items' => [
                ['title' => '媒体库', 'route' => 'admin.attachments.index', 'active' => 'admin.attachments.*'],
            ]],
            ['title' => '外观', 'items' => [
                ['title' => '菜单管理', 'route' => 'admin.menus.index', 'active' => 'admin.menus.*'],
                ['title' => '页面模板', 'route' => 'admin.page-templates.index', 'active' => 'admin.page-templates.*'],
                ['title' => '区块管理', 'route' => 'admin.blocks.index', 'active' => 'admin.blocks.*'],
            ]],
            ['title' => '表单', 'items' => [
                ['title' => '表单管理', 'route' => 'admin.forms.index', 'active' => 'admin.forms.*'],
            ]],
            ['title' => '用户', 'items' => [
                ['title' => '用户管理', 'route' => 'admin.users.index', 'active' => 'admin.users.*'],
                ['title' => '角色管理', 'route' => 'admin.roles.index', 'active' => 'admin.roles.*'],
                ['title' => '权限管理', 'route' => 'admin.permissions.index', 'active' => 'admin.permissions.*'],
            ]],
            ['title' => '运营', 'items' => [
                ['title' => '广告管理', 'route' => 'admin.ads.index', 'active' => 'admin.ads.*'],
                ['title' => '广告位', 'route' => 'admin.ad-positions.index', 'active' => 'admin.ad-positions.*'],
                ['title' => '友情链接', 'route' => 'admin.friend-links.index', 'active' => 'admin.friend-links.*'],
                ['title' => '短链接', 'route' => 'admin.short-links.index', 'active' => 'admin.short-links.*'],
                ['title' => '内容推送', 'route' => 'admin.push.index', 'active' => 'admin.push.*'],
            ]],
            ['title' => '设置', 'items' => [
                ['title' => '系统设置', 'route' => 'admin.options.index', 'active' => 'admin.options.*'],
                ['title' => '站点管理', 'route' => 'admin.sites.index', 'active' => 'admin.sites.*'],
                ['title' => '重定向', 'route' => 'admin.redirects.index', 'active' => 'admin.redirects.*'],
                ['title' => '审计日志', 'route' => 'admin.audit.index', 'active' => 'admin.audit.*'],
            ]],
        ];
    }

    /**
     * 管理面板首页。
     */
    public function index(): View
    {
        return view('admin.dashboard.index');
    }
}
