# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

**Bolão Copa 2026** — Laravel 13 betting-pool app (PHP 8.3, MySQL 8, Tailwind via Breeze). Interface in Portuguese (pt-BR), light/dark theme toggle.

## Commands

```bash
# Full dev environment (server + queue + vite concurrently)
composer dev

# Fresh DB with roles, 48 teams, 72 games, demo bets
php artisan migrate:fresh --seed

# Run test suite (Pest) — requires copa_test DB in MySQL
composer test
php artisan test
php artisan test --filter ScoringTest   # single test file

# Code style (Laravel Pint)
./vendor/bin/pint
./vendor/bin/pint --test   # check only

# Artisan commands
php artisan worldcup:sync-matches           # import/update from football-data.org
php artisan matches:send-reminders          # email users who haven't bet
php artisan schedule:run                    # run all scheduled tasks
php artisan queue:work                      # process scoring jobs and emails
```

## Testing

Tests use MySQL (`copa_test` database) — **not SQLite**. `QUEUE_CONNECTION=sync` in `phpunit.xml` so jobs run inline during tests. The `copa_test` database must exist before running tests.

## Visual Theme (Brasil)

The app uses the Brazilian national colors throughout, with full light/dark support.

| Token | Hex | Use |
|---|---|---|
| `br-green` (`#009C3B`) | Verde | Navbar (light), primary buttons, focus border |
| `br-yellow` (`#FFDF00`) | Amarelo | Active nav border/text, focus rings, ranking highlight |
| `br-blue` (`#002776`) | Azul | Page header bar (light), dark-mode navbar |
| `br-navy` (`#001a50`) | Marinho | Dark navbar / card backgrounds |
| `br-deep` (`#001233`) | Marinho profundo | Dark page background |

**Logo** (`resources/views/components/application-logo.blade.php`): SVG inline with hardcoded fills (green circle → yellow diamond → blue circle → white star). Does **not** use `fill-current` so CSS color inheritance doesn't override the multi-color design.

**Favicon** (`public/favicon.svg`): Same emblem at 32×32, linked in both layouts via `<link rel="icon" type="image/svg+xml">`.

**CSS component classes** (`resources/css/app.css` under `@layer components`): reusable Brazilian-themed utilities used across all views — avoids repeating arbitrary hex values inline:

| Class | Purpose |
|---|---|
| `.br-card` | White/dark-navy card with green top border |
| `.br-section-title` | Blue (light) / Yellow (dark) section heading |
| `.br-thead` | Green (light) / Blue+Yellow (dark) table header |
| `.br-tbody` | Row hover and divider in Brazil palette |
| `.br-btn` / `.br-btn-sm` | Green action buttons |
| `.br-btn-cancel` | Neutral cancel button |
| `.br-link` / `.br-link-danger` | Green or red inline links |
| `.br-alert-success` / `.br-alert-error` | Flash message banners |
| `.br-badge-finished` / `.br-badge-scheduled` / `.br-badge-live` | Match status pills |
| `.br-input` / `.br-select` | Dark-mode aware form fields with green focus |
| `.br-label` | Blue/Yellow form labels |

**Welcome page** (`resources/views/welcome.blade.php`): standalone landing page (no `x-app-layout`). Sections: navbar, hero gradient (green→blue), features 3-col, scoring cards, 4-step guide, CTA, footer. Uses `@include('partials.theme-script')` for dark-mode toggle and the same `@vite` pipeline. Supports `@auth` / `@guest` conditionals.

After any Tailwind/blade change, rebuild: `npm run build` (or `npm run dev`).

## Architecture

### Roles & Permissions (spatie/laravel-permission)

Three roles: `super-admin`, `admin`, `user`. Permissions: `teams.manage`, `games.manage`, `games.set-result`, `bets.create`, `users.manage`, `roles.manage`.

`Gate::before` in `AppServiceProvider` lets `super-admin` bypass all permission checks. Admin role gets manage + result permissions; user role gets only `bets.create`.

### Bet lifecycle

1. User submits a bet via `BetController@store` (requires `bets.create` permission).
2. `BetRequest` blocks bets placed within `bet_lock_buffer_minutes` (default 5) of kick-off.
3. Other users' bets for a game are hidden until the game starts (anti-fraud).
4. Knockout games reject draw predictions.

### Scoring flow (async)

When an admin posts a result (`GameController@storeResult`), it dispatches `RecalculateGamePointsJob` to the **database queue**. The job calls `ScoringService::recalculateGame()`, which iterates all bets and sets `points_earned` idempotently.

Scoring rules (non-accumulating — highest applicable wins):
- Exact score: **10 pts**
- Correct winner/draw: **5 pts**
- One team's goals correct: **1 pt** (if `partial_enabled`)
- Miss: **0 pts**

All rules are configurable in `config/bolao.php`.

### External sync (football-data.org)

`FootballDataService` calls `/v4/competitions/WC/matches`, respects rate-limit headers (`X-Requests-Available-Minute`). `WorldCupSyncMatches` command upserts teams and games, skips knockout fixtures without assigned teams, and dispatches `RecalculateGamePointsJob` for finished matches. Auditing is disabled during bulk sync to avoid log pollution.

### Ranking & Dashboard

`RankingService` computes top-10 with tiebreaking. `DashboardController` (single-action) loads today's games and ranking summary.

### Key config

| File | Purpose |
|---|---|
| `config/bolao.php` | Scoring points, bet lock buffer, display timezone |
| `config/services.php` | football-data.org token/base URL/competition |

Dates are stored in **UTC** and displayed in `America/Sao_Paulo`.

### Notifications & Queue

`BetReminderNotification` and `ResultPostedNotification` are queued via the `database` driver. In production, `php artisan queue:work` must run as a daemon (or via scheduler/supervisor).

### Auditing

`Game` model uses `owen-it/laravel-auditing`. The audit table is created by the `create_audits_table` migration. `WorldCupSyncMatches` calls `Game::disableAuditing()` / `Game::enableAuditing()` to avoid recording automated imports.
