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

    public function cssPath(): string
    {
        return $this->path('css/app.css');
    }

    public function jsPath(): string
    {
        return $this->path('js/app.js');
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
