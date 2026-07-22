<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\RolePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(fn (User $user) => $user->hasRole('super_admin') ? true : null);

        // Spatie 的 Role 模型在 vendor 命名空间下，Laravel 无法自动猜测到 App\Policies\RolePolicy
        Gate::policy(Role::class, RolePolicy::class);
    }
}
