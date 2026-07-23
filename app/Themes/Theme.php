<?php

namespace App\Themes;

use InvalidArgumentException;

class Theme
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $label,
        public readonly ?string $description,
        public readonly string $version,
        public readonly string $author,
        public readonly ?string $preview,
    ) {}

    public function path(string $path = ''): string
    {
        return resource_path('themes/'.$this->id.($path ? '/'.$path : ''));
    }

    public function viewsPath(): string
    {
        return $this->path('views');
    }

    public function cssEntry(): string
    {
        return "resources/themes/{$this->id}/css/app.css";
    }

    public function jsEntry(): string
    {
        return "resources/themes/{$this->id}/js/app.js";
    }

    public function cssPath(): string
    {
        return $this->path('css/app.css');
    }

    public function jsPath(): string
    {
        return $this->path('js/app.js');
    }

    public function isCompiled(): bool
    {
        $manifestPath = public_path('build/manifest.json');

        if (! file_exists($manifestPath)) {
            return false;
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);

        if (! is_array($manifest)) {
            return false;
        }

        return isset($manifest[$this->cssEntry()]) && isset($manifest[$this->jsEntry()]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(string $id, array $data): static
    {
        $required = ['name', 'label', 'version', 'author'];

        foreach ($required as $key) {
            if (empty($data[$key]) || ! is_string($data[$key])) {
                throw new InvalidArgumentException("Theme [{$id}] is missing required metadata [{$key}].");
            }
        }

        return new static(
            id: $id,
            name: $data['name'],
            label: $data['label'],
            description: isset($data['description']) && is_string($data['description']) ? $data['description'] : null,
            version: $data['version'],
            author: $data['author'],
            preview: isset($data['preview']) && is_string($data['preview']) ? $data['preview'] : null,
        );
    }
}
