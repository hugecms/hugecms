<?php

namespace Tests\Unit\Themes;

use App\Themes\Theme;
use App\Themes\ThemeRegistry;
use Illuminate\Filesystem\Filesystem;
use PHPUnit\Framework\TestCase;

class ThemeRegistryTest extends TestCase
{
    private string $basePath;

    private Filesystem $files;

    protected function setUp(): void
    {
        parent::setUp();

        $this->basePath = sys_get_temp_dir().'/hugecms-themes-'.uniqid();
        $this->files = new Filesystem;
        $this->files->makeDirectory($this->basePath, 0755, true);
    }

    protected function tearDown(): void
    {
        $this->files->deleteDirectory($this->basePath);

        parent::tearDown();
    }

    public function test_it_discovers_themes_from_directories(): void
    {
        $this->createTheme('blog', '博客主题', 1);
        $this->createTheme('corp', '企业主题', 2);

        $registry = new ThemeRegistry($this->basePath, $this->files);
        $themes = $registry->all();

        $this->assertCount(2, $themes);
        $this->assertSame('blog', $themes['blog']->id);
        $this->assertSame('corp', $themes['corp']->id);
    }

    public function test_themes_are_sorted_by_label(): void
    {
        $this->createTheme('zeta', 'Zeta', 1);
        $this->createTheme('alpha', 'Alpha', 2);

        $registry = new ThemeRegistry($this->basePath, $this->files);
        $labels = array_map(fn (Theme $theme): string => $theme->label, $registry->all());

        $this->assertSame(['Alpha', 'Zeta'], array_values($labels));
    }

    public function test_it_skips_directories_without_theme_json(): void
    {
        $this->files->makeDirectory($this->basePath.'/empty-theme', 0755, true);
        $this->createTheme('valid', 'Valid Theme', 1);

        $registry = new ThemeRegistry($this->basePath, $this->files);

        $this->assertCount(1, $registry->all());
        $this->assertTrue($registry->has('valid'));
        $this->assertFalse($registry->has('empty-theme'));
    }

    public function test_it_skips_themes_with_invalid_metadata(): void
    {
        $this->files->makeDirectory($this->basePath.'/invalid', 0755, true);
        $this->files->put($this->basePath.'/invalid/theme.json', json_encode(['label' => 'Missing name']));

        $registry = new ThemeRegistry($this->basePath, $this->files);

        $this->assertCount(0, $registry->all());
        $this->assertNull($registry->get('invalid'));
    }

    public function test_it_returns_null_for_unknown_theme(): void
    {
        $registry = new ThemeRegistry($this->basePath, $this->files);

        $this->assertNull($registry->get('missing'));
    }

    private function createTheme(string $id, string $label, int $sortOrder): void
    {
        $path = $this->basePath.'/'.$id;
        $this->files->makeDirectory($path, 0755, true);
        $this->files->put($path.'/theme.json', json_encode([
            'name' => $id,
            'label' => $label,
            'description' => "Description for {$label}",
            'version' => '1.0.0',
            'author' => 'Test',
            'preview' => null,
        ]));
    }
}
