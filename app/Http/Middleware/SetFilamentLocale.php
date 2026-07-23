<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetFilamentLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        app()->setLocale('zh_CN');
        app()->setFallbackLocale('en');

        return $next($request);
    }
}
