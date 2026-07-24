<?php

namespace App\Http\Controllers;

use App\Themes\ThemeRegistry;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ThemeAssetController extends Controller
{
    /**
     * Directories under a theme that are allowed to be served publicly.
     */
    protected const ALLOWED_DIRECTORIES = ['css', 'js', 'images', 'img', 'fonts', 'assets'];

    /**
     * Explicit MIME map — finfo often mis-detects plain-text assets.
     */
    protected const MIME_TYPES = [
        'css' => 'text/css; charset=UTF-8',
        'js' => 'text/javascript; charset=UTF-8',
        'mjs' => 'text/javascript; charset=UTF-8',
        'map' => 'application/json',
        'svg' => 'image/svg+xml',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'otf' => 'font/otf',
        'eot' => 'application/vnd.ms-fontobject',
    ];

    public function __construct(
        protected ThemeRegistry $themes,
    ) {}

    public function show(Request $request, string $theme, string $path): BinaryFileResponse
    {
        $theme = $this->themes->get($theme) ?? throw new NotFoundHttpException;

        $path = str_replace('\\', '/', $path);

        $directory = explode('/', $path)[0] ?? '';

        if (! in_array($directory, self::ALLOWED_DIRECTORIES, true)) {
            throw new NotFoundHttpException;
        }

        $basePath = realpath($theme->path());
        $filePath = realpath($theme->path($path));

        if ($basePath === false || $filePath === false || ! str_starts_with($filePath, $basePath.DIRECTORY_SEPARATOR) || ! is_file($filePath)) {
            throw new NotFoundHttpException;
        }

        $response = response()->file($filePath);

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if (isset(self::MIME_TYPES[$extension])) {
            $response->headers->set('Content-Type', self::MIME_TYPES[$extension]);
        }

        $response->headers->set('Cache-Control', app()->hasDebugModeEnabled()
            ? 'no-cache'
            : 'public, max-age=86400');

        return $response;
    }
}
