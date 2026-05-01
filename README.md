# rb-wp-starter

[![CI](https://github.com/rb-hubs/rb-wp-starter/actions/workflows/ci.yml/badge.svg)](https://github.com/rb-hubs/rb-wp-starter/actions/workflows/ci.yml)
[![PHP 8.2+](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![WordPress 6.4+](https://img.shields.io/badge/WordPress-6.4+-21759B?logo=wordpress&logoColor=white)](https://wordpress.org/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.4+-38B2AC?logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)

WordPress Block Theme Template für die **rb-hubs Multi-Site-Plattform**. Aus diesem Template entstehen alle Sites unter `rb-hubs/rb-site-*` – eigene Brands, Pool-Ideen, Whitelabel-Kunden.

## Was ist drin

**Frontend:**
- WordPress Block Theme (FSE) mit Tailwind-Build
- Responsive Block-Patterns, Mobile-First
- DSGVO-konformes Tracking (anonyme Counter, keine Cookies)
- JSON-LD Schema (Organization)

**Backend:**
- Customizer-System für Firmendaten (Kontakt, 2 Standorte, SMTP, Auto-Reply)
- Lead-Outbox-CPT (Audit-Trail, CRM-Sync-ready, DSGVO-Lösch-Aktion)
- Kontaktformular mit Honeypot, Nonce, Rate-Limit
- SMTP-Setup für Microsoft 365 / Hostinger
- Auto-Reply-System (HTML-Mail mit Customizer-Texten)
- Dashboard-Widget mit Lead-Stats

**Developer Experience:**
- DDEV-Setup mit Auto-WP-Install + Custom Commands (`wp-fresh`, `import-prod`, `db-dump`, `tailwind-watch`)
- CI-Pipeline: PHP-Syntax, PHPCS (WordPress-Standards), PHPStan Level 5, Build-Validation
- Pre-commit Hooks via lefthook (PHP-Lint, JSON/YAML-Validate, Conventional Commits)
- Dependabot für npm/composer/actions
- EditorConfig für konsistente IDE-Settings
- PR/Issue-Templates, CODEOWNERS, CHANGELOG

**Deploy:**
- Hostinger Git-Integration (nativ, OAuth) – kein Webhook-Krampf
- `deploy.sh` für lokales Build + Push in einem Befehl
- Quality-Gate via CI als Frühwarnsystem

## Quick Start

```bash
# 1) Repo aus Template erstellen
gh repo create rb-hubs/rb-site-MEINPROJEKT \
  --template rb-hubs/rb-wp-starter \
  --private --clone

# 2) In ~/Code/rb-hubs/ klonen (NICHT in iCloud!)
cd ~/Code/rb-hubs/rb-site-MEINPROJEKT

# 3) Token-Replace
chmod +x init-site.sh
./init-site.sh meinprojekt mp "Mein Projekt" "Beschreibung der Site" "https://meinprojekt.de" "1.0.0"

# 4) Dependencies + lokales Setup
npm install
composer install
ddev start
ddev launch
```

Detailliert: siehe **[INIT.md](INIT.md)** (Setup-Checkliste).
Deploy: siehe **[DEPLOY.md](DEPLOY.md)** (Hostinger-Konfig).
Daily-Use: siehe unten.

## Daily Workflow

```bash
# Lokal entwickeln
ddev start
ddev tailwind-watch  # CSS rebuilds on save

# Live deployen
./deploy.sh "feat(header): neues mega-menu"
```

## Struktur

```
rb-wp-starter/
├── style.css                 WordPress Theme-Header
├── functions.php             Theme-Setup, Includes, Asset-Enqueue
├── theme.json                Block-Theme-Konfig (Farben, Typo, Layout)
├── tailwind.config.js        Tailwind mit {{SITE_PREFIX}}-Farben
├── package.json              NPM-Scripts (dev/build/build:all)
├── composer.json             PHPCS + PHPStan + rb-wp-shared
├── phpcs.xml                 WordPress-Coding-Standards Override
├── phpstan.neon.dist         Static Analysis Level 5
├── lefthook.yml              Pre-commit Hooks
├── .editorconfig             IDE-Settings
├── .gitignore
├── deploy.sh                 Tailwind-Build + push
├── init-site.sh              Token-Replace beim Initial-Setup
│
├── inc/                      Generische Theme-Module
│   ├── helpers.php           Customizer-Wrapper, Site-Defaults
│   ├── customizer.php        WP-Customizer-Felder
│   ├── contact-form.php      Form-Handler (Honeypot, Nonce, Rate-Limit)
│   ├── cpt-lead.php          Lead-Outbox-CPT
│   ├── tracking.php          DSGVO-Counter-System
│   ├── smtp.php              SMTP-Konfig (M365/Hostinger)
│   ├── auto-reply.php        HTML-Mail nach Lead-Submit
│   ├── dashboard-widget.php  Lead-Stats im WP-Admin
│   └── schema-jsonld.php     Organization JSON-LD
│
├── templates/                Block-Theme HTML-Templates
│   ├── 404.html
│   ├── archive.html
│   ├── front-page.html
│   ├── index.html
│   ├── page.html
│   ├── search.html
│   └── single.html
│
├── parts/                    Header, Footer, Reusable Parts
│   ├── header.html
│   └── footer.html
│
├── blocks/                   Custom-Blocks (leer im Starter)
├── assets/                   CSS/JS/IMG (Build-Output, ignored)
├── src/css/                  Tailwind-Input
│   ├── main.css
│   └── editor.css
│
├── .ddev/                    DDEV-Setup
│   ├── config.yaml
│   └── commands/             Custom DDEV Commands
│       ├── web/wp-fresh
│       ├── web/import-prod
│       ├── web/db-dump
│       └── host/tailwind-watch
│
└── .github/
    ├── workflows/ci.yml      Quality-Gate
    ├── dependabot.yml        Auto-Updates
    ├── pull_request_template.md
    └── ISSUE_TEMPLATE/
```

## Tokens (via `init-site.sh` ersetzt)

| Token | Beispiel | Wo verwendet |
|-------|----------|--------------|
| `{{SITE_SLUG}}` | `meinprojekt` | textdomain, npm-name, composer-name |
| `{{SITE_PREFIX}}` | `mp` | Funktionsnamen, CSS-Klassen, Filter, CPT-Slug |
| `{{SITE_PREFIX_UC}}` | `MP` | Konstanten (`MP_THEME_DIR` etc.) |
| `{{SITE_NAME}}` | `Mein Projekt` | Theme-Header, Display-Strings |
| `{{SITE_DESC}}` | `WordPress-Theme für …` | Theme-Beschreibung |
| `{{SITE_URL}}` | `https://meinprojekt.de` | Theme-URI |
| `{{SITE_AUTHOR}}` | `rb-hubs` | Theme-Author |
| `{{SITE_VERSION}}` | `1.0.0` | Theme-Version |

**Hinweis zu PHP-Konstanten-Naming:** Wir nutzen `<PREFIX>_THEME_DIR/URI/VERSION` statt `<PREFIX>_DIR/URI/VERSION`, weil `T_DIR` mit der PHP-Tokenizer-Konstante kollidiert (Single-Letter-Prefix). Das `_THEME_`-Suffix vermeidet alle Konflikte.

## Was hier NICHT drin ist

Site-spezifische Komponenten landen im Composer-Paket `rb-hubs/rb-wp-shared`:
- Premium-HTML-Mail-Templates (Auto-Reply mit Logo, Tabellen-Layout)
- CRM-Adapter (Pipedrive, HubSpot, etc.)
- Mega-Menus mit Auto-Population aus Posts/CPTs
- Block-Pattern-Library (Hero, Cards, Testimonials, FAQ)
- Block-Patterns für Förderpakete / Konfiguratoren
- Schema-JSONLD-Builder mit Article/LocalBusiness/Product

Per `composer require rb-hubs/rb-wp-shared` einbinden.

## Versionierung

Folgt [Semantic Versioning](https://semver.org/spec/v2.0.0.html). Siehe **[CHANGELOG.md](CHANGELOG.md)** für Release-Notes.

## Contributing

Siehe **[.github/pull_request_template.md](.github/pull_request_template.md)** für PR-Anforderungen.

Pre-commit Hooks aktivieren:
```bash
brew install lefthook
lefthook install
```

Conventional Commits werden enforced: `feat(scope):`, `fix(scope):`, `chore(deps):` etc.

## Lizenz

GPL-2.0-or-later (WordPress-Standard).
