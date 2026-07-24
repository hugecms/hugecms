# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project overview

HugeCMS is an enterprise content management system built on **Laravel 13.20** and **Filament 5.7**, targeting **PHP ^8.3**. The Filament admin panel (`/admin`) is fully implemented under `app/Filament/` but is being migrated to a standalone Vue SPA in `packages/admin/`; the Laravel front end uses server-rendered Blade themes with plain css/js (no build pipeline).

## Common commands

### Setup

```bash
composer setup
```

This runs: `composer install`, copies `.env.example` to `.env`, generates an app key, and runs migrations.

### Development

```bash
composer dev
```

Runs `php artisan serve`. There is no frontend build step for the Laravel app — front-end theme assets are plain css/js served directly.

Other services can be run individually as needed:

```bash
php artisan queue:listen --tries=1 --timeout=0
php artisan pail --timeout=0
```

### Front-end theme assets

Themes carry plain, un-built `css/app.css` and `js/app.js` under `resources/themes/{theme}/`. They are served at runtime through the `themes/{theme}/{path}` route (`App\Http\Controllers\ThemeAssetController`); Blade layouts reference them via the `theme_asset()` helper. Do not reintroduce a build pipeline (Vite/Tailwind/npm) for theme assets.

Filament/Livewire assets are served from the vendor directory; run `php artisan filament:assets` if Filament static assets need to be republished. (Filament is being phased out in favor of the Vue admin app in `packages/admin/`.)

### Tests

```bash
# Run the full suite (uses SQLite in-memory via phpunit.xml)
composer test

# Equivalent artisan commands
php artisan test
php artisan test --filter=ExampleTest
php artisan test tests/Feature/ExampleTest.php
```

`phpunit.xml` configures the testing environment: `APP_ENV=testing`, `DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`, `QUEUE_CONNECTION=sync`, `SESSION_DRIVER=array`, `CACHE_STORE=array`.

### Code style

```bash
./vendor/bin/pint
./vendor/bin/pint --test
```

The project uses Laravel Pint for PHP formatting. The default preset is applied (no custom `pint.json` is present).

### Migrations and database

```bash
php artisan migrate
php artisan migrate:fresh --seed
php artisan db:seed
```

The default `.env.example` is configured for MySQL (`DB_CONNECTION=mysql`). Testing uses SQLite in-memory regardless of the local dev database.

## High-level architecture

### Laravel bootstrap

The application is bootstrapped in `bootstrap/app.php` using Laravel 13's fluent `Application::configure()` API. Routing is limited to `routes/web.php` and `routes/console.php`; there is no `routes/api.php` yet. A health-check endpoint is registered at `/up`.

### Service providers

`App\Providers\AppServiceProvider` and `App\Providers\ThemeServiceProvider` are registered in `bootstrap/providers.php`. Filament panel providers (e.g. `App\Providers\Filament\AdminPanelProvider`) are also registered there.

### Frontend stack

- **No build pipeline** for the Laravel front end: no Vite, Tailwind, or npm at the repository root. Theme assets are hand-written, plain CSS/JS.
- **Theme system**: front-end themes live under `resources/themes/{theme}/`, each with its own `views/`, `css/app.css`, and `js/app.js`. The active theme is resolved at runtime and its view path is prepended to Laravel's view finder; missing views fall back to the `default` theme.
- Theme assets are served through the `themes/{theme}/{path}` route (`ThemeAssetController`, restricted to `css|js|images|img|fonts|assets` directories); Blade layouts load them with the `theme_asset()` helper in `app/helpers.php`.
- Pagination uses a semantic, theme-agnostic view at `resources/views/vendor/pagination/default.blade.php` (classes `pagination`, `pagination-item`, `pagination-link`, `active`, `disabled`) — themes style these in their own CSS.
- `Instrument Sans` (weights 400/500/600) is loaded from Bunny Fonts via a plain `<link>` in the theme layouts.
- **Admin app**: `packages/admin/` is a standalone Vue 3 + Vite + Element Plus SPA (pnpm) that will replace Filament; it is built and deployed separately, with Laravel serving its `dist` at `/admin`.

### Filament state

The Filament admin panel under `app/Filament/` is the current admin backend, but it is being migrated to the Vue SPA in `packages/admin/` (API-driven, Sanctum cookie auth). Do not scaffold new Filament resources; new admin functionality goes into `packages/admin/` plus API endpoints. After upgrading Filament while it remains, run `php artisan filament:assets` to republish static assets.

## Conventions

- PHP code follows the `.editorconfig`: 4-space indentation, LF line endings, UTF-8, final newlines.
- Eloquent models use PHP 8 attributes for mass-assignment and hidden fields (e.g. `#[Fillable([...])]`, `#[Hidden([...])]`) rather than property arrays.
- Models live under `App\Models\`, migrations under `database/migrations/`, factories under `database/factories/`, seeders under `database/seeders/`.
- Tests use the PHPUnit layout in `tests/Unit/` and `tests/Feature/` with the `Tests\` namespace.

## Agent skills

### Issue tracker

Issues are tracked as GitHub issues on `hugecms/hugecms`. See `docs/agents/issue-tracker.md`.

### Triage labels

Using the default five canonical labels: `needs-triage`, `needs-info`, `ready-for-agent`, `ready-for-human`, `wontfix`. See `docs/agents/triage-labels.md`.

### Domain docs

Single-context: `CONTEXT.md` at the repo root, ADRs under `docs/adr/`. See `docs/agents/domain.md`.
