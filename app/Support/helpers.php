<?php

if (! function_exists('is_email')) {
    /**
     * 验证邮箱地址格式
     */
    function is_email(string $email): bool
    {
        return ! (filter_var($email, FILTER_VALIDATE_EMAIL) === false);
    }
}

if (! function_exists('is_mobile')) {
    /**
     * 验证手机号码格式
     */
    function is_mobile(string $mobile): bool
    {
        $rule = '/^1[3-9]\d{9}$/';

        return preg_match($rule, $mobile) === 1;
    }
}

if (! function_exists('theme')) {
    /**
     * 主题文件链接
     */
    function theme(string $path = '', string $default = ''): string
    {
        if (empty($default)) {
            $default = config('app.default_theme', 'default');
        }

        return asset('themes/'.$default.'/'.ltrim($path, '/'));
    }
}
