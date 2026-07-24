<?php

namespace Tests\Feature\Themes;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThemeAssetControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_serves_theme_css_assets(): void
    {
        $response = $this->get('/themes/default/css/app.css');

        $response->assertOk();
        $this->assertSame('text/css; charset=UTF-8', $response->headers->get('Content-Type'));
    }

    public function test_it_serves_theme_js_assets(): void
    {
        $this->get('/themes/default/js/app.js')->assertOk();
    }

    public function test_it_rejects_unknown_themes(): void
    {
        $this->get('/themes/not-a-theme/css/app.css')->assertNotFound();
    }

    public function test_it_rejects_disallowed_directories(): void
    {
        $this->get('/themes/default/views/layouts/app.blade.php')->assertNotFound();
        $this->get('/themes/default/theme.json')->assertNotFound();
    }

    public function test_it_rejects_path_traversal(): void
    {
        $this->get('/themes/default/css/../../composer.json')->assertNotFound();
        $this->get('/themes/default/css/..%2F..%2Fcomposer.json')->assertNotFound();
    }
}
