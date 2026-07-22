<?php

namespace App\Support;

class Permissions
{
    /**
     * 权限目录：{模型key} => ['label' => 中文名, 'actions' => {动作} => 中文名]
     *
     * 权限命名规范：{model}.{action}，如 article.create、comment.delete
     *
     * @return array<string, array{label: string, actions: array<string, string>}>
     */
    public static function all(): array
    {
        $standardActions = [
            'view_any' => '查看',
            'create' => '新增',
            'update' => '编辑',
            'delete' => '删除',
        ];

        return [
            'article' => ['label' => '文章', 'actions' => $standardActions + ['restore' => '恢复', 'force_delete' => '彻底删除']],
            'page' => ['label' => '页面', 'actions' => $standardActions + ['restore' => '恢复', 'force_delete' => '彻底删除']],
            'category' => ['label' => '分类', 'actions' => $standardActions],
            'tag' => ['label' => '标签', 'actions' => $standardActions],
            'comment' => ['label' => '评论', 'actions' => $standardActions],
            'media' => ['label' => '媒体资源', 'actions' => $standardActions],
            'media_category' => ['label' => '媒体分类', 'actions' => $standardActions],
            'banner' => ['label' => '轮播图', 'actions' => $standardActions],
            'announcement' => ['label' => '公告', 'actions' => $standardActions],
            'link' => ['label' => '友情链接', 'actions' => $standardActions],
            'user' => ['label' => '用户', 'actions' => $standardActions],
            'role' => ['label' => '角色', 'actions' => $standardActions],
        ];
    }

    /**
     * 后台访问权限
     */
    public const ACCESS_ADMIN = 'access_admin';

    /**
     * 所有权限名（扁平列表，含后台访问权限）
     *
     * @return array<int, string>
     */
    public static function names(): array
    {
        $names = [self::ACCESS_ADMIN];

        foreach (self::all() as $model => $config) {
            foreach (array_keys($config['actions']) as $action) {
                $names[] = "{$model}.{$action}";
            }
        }

        return $names;
    }

    /**
     * 指定模型的全部权限名
     *
     * @return array<int, string>
     */
    public static function forModel(string $model, ?array $actions = null): array
    {
        $config = self::all()[$model] ?? null;

        if (! $config) {
            return [];
        }

        $actions ??= array_keys($config['actions']);

        return array_map(fn (string $action) => "{$model}.{$action}", $actions);
    }

    /**
     * 权限的中文显示名，如 "文章 · 新增"
     */
    public static function label(string $permission): string
    {
        if ($permission === self::ACCESS_ADMIN) {
            return '访问后台';
        }

        [$model, $action] = explode('.', $permission, 2);

        $config = self::all()[$model] ?? null;

        if (! $config) {
            return $permission;
        }

        return $config['label'].' · '.($config['actions'][$action] ?? $action);
    }
}
