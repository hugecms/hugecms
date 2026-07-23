<?php

namespace App\Http\Middleware;

use App\Themes\ThemeResolver;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetTheme
{
    public function __construct(protected ThemeResolver $resolver) {}

    public function handle(Request $request, Closure $next): Response
    {
        $active = $this->resolver->active();
        $default = $this->resolver->default();

        app()->instance('theme.active', $active);

        $finder = View::getFinder();
        $paths = $finder->getPaths();
        $themePrefix = resource_path('themes');

        $otherPaths = array_values(array_filter(
            $paths,
            fn (string $path): bool => ! str_starts_with($path, $themePrefix),
        ));

        $themePaths = [$default->viewsPath()];

        if ($active->id !== $default->id) {
            array_unshift($themePaths, $active->viewsPath());
        }

        $finder->setPaths([...$themePaths, ...$otherPaths]);

        return $next($request);
    }
}
