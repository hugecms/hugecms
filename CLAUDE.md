# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project overview

HugeCMS is an enterprise content management system built on **Laravel 13.20** and **Filament 5.7**, targeting **PHP ^8.3**. It is currently a freshly initialized Laravel application: Filament is a Composer dependency but no panel provider or resources have been scaffolded yet (`app/Providers/` only contains `AppServiceProvider.php`).

## Common commands

### Setup

```bash
composer setup
```

This runs: `composer install`, copies `.env.example` to `.env`, generates an app key, runs migrations, installs npm dependencies, and builds frontend assets.

### Development

```bash
composer dev
```

Runs the PHP dev server, queue listener, log tail (`pail`), and Vite dev server concurrently via `npx concurrently`. All four processes are killed together when any exits.

Alternatively run services individually:

```bash
php artisan serve
php artisan queue:listen --tries=1 --timeout=0
php artisan pail --timeout=0
npm run dev
```

### Build

```bash
npm run build
```

Compiles `resources/css/app.css` and `resources/js/app.js` through Vite. Filament/Livewire assets are served from the vendor directory; run `php artisan filament:assets` if Filament static assets need to be republished.

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

Only `App\Providers\AppServiceProvider` is registered in `bootstrap/providers.php`. Once Filament panels are created, their panel provider classes should be added here.

### Frontend stack

- **Vite** with the `laravel-vite-plugin` (v3) for asset bundling.
- **Tailwind CSS v4** imported as a Vite plugin (`@tailwindcss/vite`).
- CSS entry is `resources/css/app.css`, which uses `@import 'tailwindcss'` and defines the `--font-sans` theme variable as `Instrument Sans`.
- The `laravel-vite-plugin/fonts` helper loads `Instrument Sans` weights 400/500/600 from Bunny Fonts.
- The Vite dev server ignores changes under `storage/framework/views/**` to reduce churn from compiled Blade templates.

### Filament state

Filament packages are present in `vendor/` and `artisan` exposes `filament:*` commands, but no application-level Filament code exists yet. To create the first admin panel, run `php artisan filament:install`. This will generate the panel provider, resources directory, and service provider registration. After installing or upgrading Filament, run `php artisan filament:assets` to publish/republish static assets.

## Conventions

- PHP code follows the `.editorconfig`: 4-space indentation, LF line endings, UTF-8, final newlines.
- Eloquent models use PHP 8 attributes for mass-assignment and hidden fields (e.g. `#[Fillable([...])]`, `#[Hidden([...])]`) rather than property arrays.
- Models live under `App\Models\`, migrations under `database/migrations/`, factories under `database/factories/`, seeders under `database/seeders/`.
- Tests use the PHPUnit layout in `tests/Unit/` and `tests/Feature/` with the `Tests\` namespace.

## Agent skills

### Issue tracker

Issues are tracked as GitHub issues on `hugecms/hugecms`. See `docs/agents/issue-tracker.md`.

### Domain docs

Single-context: `CONTEXT.md` at the repo root, ADRs under `docs/adr/`. See `docs/agents/domain.md`.
