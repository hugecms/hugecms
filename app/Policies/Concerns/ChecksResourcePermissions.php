<?php

namespace App\Policies\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * 统一按 "{model}.{action}" 权限名检查资源操作权限。
 *
 * 使用方只需声明 protected static string $permissionModel = 'article';
 */
trait ChecksResourcePermissions
{
    protected function checkPermission(User $user, string $action): bool
    {
        return $user->can(static::$permissionModel.'.'.$action);
    }

    public function viewAny(User $user): bool
    {
        return $this->checkPermission($user, 'view_any');
    }

    /**
     * 权限目录未区分 view 与 view_any，单条查看沿用列表查看权限。
     */
    public function view(User $user, Model $model): bool
    {
        return $this->checkPermission($user, 'view_any');
    }

    public function create(User $user): bool
    {
        return $this->checkPermission($user, 'create');
    }

    public function update(User $user, Model $model): bool
    {
        return $this->checkPermission($user, 'update');
    }

    public function delete(User $user, Model $model): bool
    {
        return $this->checkPermission($user, 'delete');
    }

    public function deleteAny(User $user): bool
    {
        return $this->checkPermission($user, 'delete');
    }

    public function restore(User $user, Model $model): bool
    {
        return $this->checkPermission($user, 'restore');
    }

    public function restoreAny(User $user): bool
    {
        return $this->checkPermission($user, 'restore');
    }

    public function forceDelete(User $user, Model $model): bool
    {
        return $this->checkPermission($user, 'force_delete');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $this->checkPermission($user, 'force_delete');
    }
}
