<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        $this->loadModules();

        // 登录限流：按邮箱+IP，每分钟 5 次（仅计失败，控制器手动调用）
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by(Str::lower($request->string('email')).'|'.$request->ip());
        });

        // 认证类接口限流：按 IP，每分钟 5 次（注册/忘记密码/重置密码）
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        // 路由名为 reset-password（非框架默认的 password.reset），覆盖默认重置链接
        ResetPassword::createUrlUsing(function (CanResetPassword $notifiable, string $token) {
            return url('/reset-password?token='.$token.'&email='.urlencode($notifiable->getEmailForPasswordReset()));
        });
    }

    private function loadModules(): void
    {
        $modules = glob(app_path('Modules/*'), GLOB_ONLYDIR);
        foreach ($modules as $module) {
            $namespace = Str::lower(basename($module));
            View::addNamespace($namespace, $module.'/Views');
        }
    }
}
