<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * CMS 初始种子（database/seeder.sql 的 Laravel 版本，表名无 cms_ 前缀）。
 *
 * 使用方式：php artisan migrate:fresh --seed
 * 说明：配合 2026_06_10_* 系列迁移使用，所有 INSERT 使用显式主键ID，
 *       保证外键引用确定、可重复阅读。
 * ⚠️  默认管理员 admin / password（Hash::make 动态生成），上线前必须修改：
 *     php artisan tinker --execute="DB::table('users')->where('id',1)->update(['password'=>bcrypt('新密码')]);"
 */
class CmsSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedUsersAndRoles();
        $this->seedPermissions();
        $this->seedRoleBindings();
        $this->seedArticleModel();
        $this->seedTaxonomies();
        $this->seedSiteAndAppearance();
        $this->seedOptions();

        $this->command?->info('CMS 种子完成：管理员 admin / password（上线前必须修改）。');
    }

    /** 一、管理员账户 + 4 个内置角色 */
    private function seedUsersAndRoles(): void
    {
        DB::table('users')->insert($this->stamp([
            [
                'id' => 1,
                'name' => '管理员',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 1,
            ],
        ]));

        DB::table('roles')->insert($this->stamp([
            ['id' => 1, 'name' => '超级管理员', 'alias' => 'super_admin', 'is_system' => 1, 'description' => '拥有全部权限，不受数据范围限制'],
            ['id' => 2, 'name' => '编辑', 'alias' => 'editor', 'is_system' => 1, 'description' => '管理全站内容、分类、评论、外观与推广，不能管理用户和系统'],
            ['id' => 3, 'name' => '作者', 'alias' => 'author', 'is_system' => 1, 'description' => '仅能撰写和编辑自己的内容（data_scope=self），提交待审核'],
            ['id' => 4, 'name' => '审核员', 'alias' => 'auditor', 'is_system' => 1, 'description' => '负责内容与评论的审核，无编辑权限'],
        ]));
    }

    /** 二、权限树：9 个一级模块 + 44 个操作项，共 53 条 */
    private function seedPermissions(): void
    {
        // [模块名, 模块标识, [[操作名, 权限代码, 排序], ...]]
        $tree = [
            ['内容管理', 'content', [
                ['内容查看', 'content:view', 10],
                ['内容新增', 'content:create', 20],
                ['内容编辑', 'content:edit', 30],
                ['内容删除', 'content:delete', 40],
                ['内容发布/下线', 'content:publish', 50],
                ['内容审核', 'content:audit', 60],
                ['版本回滚', 'content:revision', 70],
                ['模型查看', 'model:view', 80],
                ['模型与字段管理', 'model:manage', 90],
                ['SEO设置', 'seo:meta', 100],
                ['重定向管理', 'redirect:manage', 110],
            ]],
            ['分类管理', 'taxonomy', [
                ['分类查看', 'taxonomy:view', 10],
                ['分类管理', 'taxonomy:manage', 20],
            ]],
            ['评论管理', 'comment', [
                ['评论查看', 'comment:view', 10],
                ['评论审核', 'comment:moderate', 20],
                ['评论删除', 'comment:delete', 30],
            ]],
            ['媒体管理', 'attachment', [
                ['媒体库查看', 'attachment:view', 10],
                ['上传附件', 'attachment:upload', 20],
                ['删除附件', 'attachment:delete', 30],
            ]],
            ['外观管理', 'appearance', [
                ['模板查看', 'template:view', 10],
                ['模板管理', 'template:manage', 20],
                ['区块查看', 'block:view', 30],
                ['区块管理', 'block:manage', 40],
                ['菜单查看', 'menu:view', 50],
                ['菜单管理', 'menu:manage', 60],
            ]],
            ['表单管理', 'form', [
                ['表单查看', 'form:view', 10],
                ['表单管理', 'form:manage', 20],
                ['表单数据查看', 'form:submission', 30],
            ]],
            ['用户权限', 'user', [
                ['用户查看', 'user:view', 10],
                ['用户新增', 'user:create', 20],
                ['用户编辑', 'user:edit', 30],
                ['用户删除', 'user:delete', 40],
                ['角色查看', 'role:view', 50],
                ['角色管理', 'role:manage', 60],
            ]],
            ['系统管理', 'system', [
                ['系统设置', 'option:manage', 10],
                ['审计日志', 'audit:view', 20],
                ['回收站', 'recycle:manage', 30],
                ['站点管理', 'site:manage', 60],
                ['数据统计', 'statistics:view', 100],
            ]],
            ['推广管理', 'marketing', [
                ['广告查看', 'ad:view', 10],
                ['广告管理', 'ad:manage', 20],
                ['友链查看', 'friend_link:view', 30],
                ['友链管理', 'friend_link:manage', 40],
                ['短链管理', 'short_link:manage', 50],
                ['内容推送', 'push:manage', 60],
            ]],
        ];

        $rows = [];
        $id = 0;
        $moduleSort = 100;
        foreach ($tree as [$moduleName, $module, $children]) {
            $id++;
            $parentId = $id;
            $rows[] = ['id' => $id, 'parent_id' => 0, 'name' => $moduleName, 'code' => $module, 'module' => $module, 'sort' => $moduleSort];
            $moduleSort += 100;

            foreach ($children as [$name, $code, $childSort]) {
                $id++;
                $rows[] = ['id' => $id, 'parent_id' => $parentId, 'name' => $name, 'code' => $code, 'module' => $module, 'sort' => $childSort];
            }
        }

        DB::table('permissions')->insert($this->stamp($rows));
    }

    /** 三、角色-权限绑定 + 管理员绑定超管角色 */
    private function seedRoleBindings(): void
    {
        $codeToId = DB::table('permissions')->pluck('id', 'code');

        $editorCodes = [
            'content:view', 'content:create', 'content:edit', 'content:delete', 'content:publish',
            'content:audit', 'content:revision', 'model:view', 'seo:meta', 'redirect:manage',
            'taxonomy:view', 'taxonomy:manage',
            'comment:view', 'comment:moderate', 'comment:delete',
            'attachment:view', 'attachment:upload', 'attachment:delete',
            'template:view', 'template:manage', 'block:view', 'block:manage',
            'menu:view', 'menu:manage',
            'recycle:manage', 'statistics:view',
            'ad:view', 'ad:manage', 'friend_link:view', 'friend_link:manage',
            'short_link:manage', 'push:manage',
        ];

        $authorCodes = [
            'content:view', 'content:create', 'content:edit',
            'taxonomy:view', 'comment:view',
            'attachment:view', 'attachment:upload',
        ];

        $auditorCodes = [
            'content:view', 'content:audit',
            'comment:view', 'comment:moderate',
            'statistics:view',
        ];

        $rows = [];

        // 超级管理员：全部权限
        foreach ($codeToId as $permissionId) {
            $rows[] = ['role_id' => 1, 'permission_id' => $permissionId, 'is_denied' => 0];
        }

        foreach ([2 => $editorCodes, 3 => $authorCodes, 4 => $auditorCodes] as $roleId => $codes) {
            foreach ($codes as $code) {
                $rows[] = ['role_id' => $roleId, 'permission_id' => $codeToId[$code], 'is_denied' => 0];
            }
        }

        DB::table('role_permissions')->insert(array_map(
            fn (array $row): array => $row + ['created_at' => now()],
            $rows
        ));

        // 管理员绑定超级管理员角色（数据范围：全部）
        DB::table('user_roles')->insert([
            ['user_id' => 1, 'role_id' => 1, 'data_scope' => 'all', 'created_at' => now()],
        ]);
    }

    /** 四、默认内容模型（文章）：注册模型 → 定义字段（物理列 data_article.field_1~3 已由迁移创建；置顶已上移为 contents.is_top 公共列） */
    private function seedArticleModel(): void
    {
        DB::table('content_models')->insert($this->stamp([
            ['id' => 1, 'name' => '文章', 'alias' => 'article', 'table_name' => 'data_article', 'description' => '系统内置的文章模型，对标 WordPress Post', 'is_system' => 1, 'status' => 1, 'sort' => 100],
        ]));

        DB::table('model_fields')->insert($this->stamp([
            ['id' => 1, 'model_id' => 1, 'field_name' => 'summary', 'column_name' => 'field_1', 'field_label' => '文章摘要', 'field_type' => 'text', 'column_type' => 'varchar(500)', 'default_value' => '', 'is_required' => 0, 'is_unique' => 0, 'sort_order' => 10],
            ['id' => 2, 'model_id' => 1, 'field_name' => 'content', 'column_name' => 'field_2', 'field_label' => '正文内容', 'field_type' => 'rich_text', 'column_type' => 'longtext', 'default_value' => null, 'is_required' => 1, 'is_unique' => 0, 'sort_order' => 20],
            ['id' => 3, 'model_id' => 1, 'field_name' => 'cover_image', 'column_name' => 'field_3', 'field_label' => '封面图', 'field_type' => 'image', 'column_type' => 'varchar(255)', 'default_value' => '', 'is_required' => 0, 'is_unique' => 0, 'sort_order' => 30],
        ]));
    }

    /** 五、默认分类法与分类项（对标 WP 的 category / tag） */
    private function seedTaxonomies(): void
    {
        DB::table('taxonomies')->insert($this->stamp([
            ['id' => 1, 'name' => '文章分类', 'alias' => 'category', 'model_id' => 1, 'is_hierarchical' => 1, 'description' => '层级分类目录（绑定文章模型）'],
            ['id' => 2, 'name' => '标签', 'alias' => 'tag', 'model_id' => 1, 'is_hierarchical' => 0, 'description' => '非层级标签（绑定文章模型）'],
        ]));

        DB::table('terms')->insert($this->stamp([
            ['id' => 1, 'taxonomy_id' => 1, 'name' => '未分类', 'slug' => 'uncategorized', 'parent_id' => 0, 'description' => '默认分类，无法删除（对标 WP 默认分类）', 'sort' => 0],
        ]));
    }

    /** 六、默认站点、菜单、模板 */
    private function seedSiteAndAppearance(): void
    {
        // 主站点（单站点模式仅此一条记录；domain 上线后改为实际域名）
        DB::table('sites')->insert($this->stamp([
            ['id' => 1, 'site_name' => '主站', 'site_code' => 'main', 'domain' => 'localhost', 'timezone' => 'Asia/Shanghai', 'language' => 'zh_CN', 'status' => 1],
        ]));

        // 菜单集（主导航 + 底部导航）
        DB::table('nav_menus')->insert($this->stamp([
            ['id' => 1, 'name' => '主导航', 'alias' => 'main_nav', 'description' => '顶部主导航'],
            ['id' => 2, 'name' => '底部导航', 'alias' => 'footer_nav', 'description' => '页脚导航'],
        ]));

        // 主导航默认项：首页（自定义链接）+ 未分类（演示 term 类型链接）
        DB::table('nav_items')->insert($this->stamp([
            ['id' => 1, 'menu_id' => 1, 'parent_id' => 0, 'title' => '首页', 'link_type' => 'custom', 'link_value' => '/', 'open_type' => 0, 'sort' => 100, 'is_active' => 1],
            ['id' => 2, 'menu_id' => 1, 'parent_id' => 0, 'title' => '未分类', 'link_type' => 'term', 'link_value' => '1', 'open_type' => 0, 'sort' => 200, 'is_active' => 1],
        ]));

        // 默认模板（文章详情 + 独立页面）
        DB::table('page_templates')->insert($this->stamp([
            ['id' => 1, 'template_name' => '默认文章模板', 'template_code' => 'post-default', 'category' => 'post', 'content' => '{"layout":"single","sidebar":true}', 'is_default' => 1, 'is_system' => 1, 'status' => 1],
            ['id' => 2, 'template_name' => '默认页面模板', 'template_code' => 'page-default', 'category' => 'page', 'content' => '{"layout":"single","sidebar":false}', 'is_default' => 1, 'is_system' => 1, 'status' => 1],
        ]));
    }

    /** 七、全局配置（options） */
    private function seedOptions(): void
    {
        DB::table('options')->insert($this->stamp([
            // 站点名称以 sites.site_name 为准，避免双源
            ['option_key' => 'site_info', 'option_value' => '{"slogan":"基于 Laravel 的类 WordPress 建站系统","icp_number":""}', 'autoload' => 1],
            ['option_key' => 'permalink', 'option_value' => '{"content":"/{slug}","term":"/{taxonomy_alias}/{slug}"}', 'autoload' => 1],
            ['option_key' => 'comment_config', 'option_value' => '{"require_moderation":true,"guest_allowed":true,"guest_must_fill":["name","email"],"max_links":0}', 'autoload' => 1],
            ['option_key' => 'seo_defaults', 'option_value' => '{"robots":"index,follow","sitemap_enabled":true,"title_separator":"-"}', 'autoload' => 1],
            ['option_key' => 'storage_config', 'option_value' => '{"driver":"local"}', 'autoload' => 1],
            ['option_key' => 'smtp_config', 'option_value' => '{"driver":"log"}', 'autoload' => 0],
        ]));
    }

    /** 为行数据补充 created_at / updated_at（DB::table 查询构造器不走 Eloquent 时间戳） */
    private function stamp(array $rows): array
    {
        $now = now()->format('Y-m-d H:i:s');

        return array_map(fn (array $row): array => $row + ['created_at' => $now, 'updated_at' => $now], $rows);
    }
}
