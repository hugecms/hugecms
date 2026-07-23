<?php

namespace App\Themes;

use Illuminate\Filesystem\Filesystem;
use InvalidArgumentException;

class ThemeRegistry
{
    /**
     * @var array<string, Theme>|null
     */
    protected ?array $cachedThemes = null;

    public function __construct(
        protected string $basePath,
        protected Filesystem $files,
    ) {}

    public static function defaultBasePath(): string
    {
        return resource_path('themes');
    }

    /**
     * @return array<string, Theme>
     */
    public function all(): array
    {
        if ($this->cachedThemes !== null) {
            return $this->cachedThemes;
        }

        if (! $this->files->isDirectory($this->basePath)) {
            return $this->cachedThemes = [];
        }

        $themes = [];

        foreach ($this->files->directories($this->basePath) as $directory) {
            $theme = $this->readDirectory($directory);

            if ($theme !== null) {
                $themes[$theme->id] = $theme;
            }
        }

        uasort($themes, fn (Theme $a, Theme $b): int => $a->label <=> $b->label);

        return $this->cachedThemes = $themes;
    }

    public function get(string $id): ?Theme
    {
        return $this->all()[$id] ?? null;
    }

    public function has(string $id): bool
    {
        return $this->get($id) !== null;
    }

    protected function readDirectory(string $directory): ?Theme
    {
        $id = basename($directory);
        $metadataPath = $directory.'/theme.json';

        if (! $this->files->isFile($metadataPath)) {
            return null;
        }

        $contents = $this->files->get($metadataPath);
        $data = json_decode($contents, true);

        if (! is_array($data)) {
            return null;
        }

        try {
            return Theme::fromArray($id, $data);
        } catch (InvalidArgumentException) {
            return null;
        }
    }
}
