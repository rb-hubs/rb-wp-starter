# rb-wp-starter

WordPress Block Theme Template für die rb-hubs Multi-Site-Plattform.

Aus diesem Template entstehen alle neuen Sites unter `rb-hubs/rb-site-*`. Es enthält:

- WordPress Block Theme mit Tailwind-Build
- Customizer-System für Firmendaten (Kontakt, Standorte, SMTP, Auto-Reply)
- Lead-Outbox-CPT (DSGVO-clean, Audit-Trail, CRM-Sync-ready)
- Kontaktformular mit Honeypot, Nonce, Rate-Limit
- DSGVO-konformes Tracking (anonyme Counter)
- SMTP-Setup für Microsoft 365 / Hostinger
- Auto-Reply-System (HTML-Mail mit Customizer-Texten)
- JSON-LD Schema (Organization)
- Dashboard-Widget mit Lead-Stats
- Vorbereitet für `rb-wp-shared` (Composer-Paket mit gemeinsamen Komponenten)
- DDEV-Setup für lokales Development
- GitHub Actions Deploy-Workflow nach Hostinger

## Neue Site aus diesem Template aufsetzen

```bash
# 1) Repo aus Template erstellen
gh repo create rb-hubs/rb-site-MEINPROJEKT --template rb-hubs/rb-wp-starter --private --clone

# 2) In ~/Code/rb-hubs/ klonen (NICHT in iCloud!)
cd ~/Code/rb-hubs
gh repo clone rb-hubs/rb-site-MEINPROJEKT
cd rb-site-MEINPROJEKT

# 3) Token-Replace ausführen
./init-site.sh meinprojekt mp "Mein Projekt" "Beschreibung der Site" "https://meinprojekt.de" "1.0.0"

# 4) DDEV starten
ddev start

# 5) Tailwind build
npm install
npm run build
```

Detaillierte Setup-Anleitung: siehe `INIT.md`.

## Struktur

```
rb-wp-starter/
├── style.css             WordPress Theme-Header
├── functions.php         Theme-Setup, Includes, Asset-Enqueue
├── theme.json            Block-Theme-Konfiguration (Farben, Typo, Layout)
├── tailwind.config.js    Tailwind-Setup mit {{SITE_PREFIX}}-Farben
├── package.json          NPM-Scripts (dev/build)
├── composer.json         Composer-Setup für rb-wp-shared
├── inc/
│   ├── helpers.php           Customizer-Wrapper, Site-Defaults
│   ├── customizer.php        WP-Customizer-Felder
│   ├── contact-form.php      Form-Handler mit Honeypot/Nonce/Rate-Limit
│   ├── cpt-lead.php          Lead-Outbox-CPT
│   ├── tracking.php          DSGVO-Counter-System
│   ├── smtp.php              SMTP-Konfiguration (M365/Hostinger)
│   ├── auto-reply.php        HTML-Mail nach Lead-Submit
│   ├── dashboard-widget.php  Lead-Stats im WP-Admin
│   └── schema-jsonld.php     Organization JSON-LD
├── templates/            Block-Theme HTML-Templates
├── parts/                Header, Footer, etc.
├── blocks/               Custom-Blocks (leer im Starter)
├── assets/               CSS/JS/IMG-Build-Output
├── src/css/              Tailwind-Input
├── .ddev/config.yaml     DDEV-Setup (Task #3 ergänzt)
├── .github/workflows/    GitHub Actions (Task #4 ergänzt)
└── init-site.sh          Token-Replace-Script
```

## Tokens (werden via init-site.sh ersetzt)

| Token | Beispiel | Wo |
|-------|----------|-----|
| `{{SITE_SLUG}}` | `meinprojekt` | textdomain, npm-name |
| `{{SITE_PREFIX}}` | `mp` | Funktionsnamen, CSS-Klassen, Filter |
| `{{SITE_PREFIX_UC}}` | `MP` | Konstanten, Env-Variablen |
| `{{SITE_NAME}}` | `Mein Projekt` | Theme-Header, Display-Strings |
| `{{SITE_DESC}}` | `WordPress Block Theme für …` | Theme-Beschreibung |
| `{{SITE_URL}}` | `https://meinprojekt.de` | Theme-URI, Composer-Repository |
| `{{SITE_AUTHOR}}` | `rb-hubs` | Theme-Author |
| `{{SITE_VERSION}}` | `1.0.0` | Theme-Version |

## Was hier NICHT drin ist

- Site-spezifische Patterns / Templates (z.B. PP-Förderrechner)
- CRM-Integrationen (Pipedrive, HubSpot etc.) → kommen aus `rb-wp-shared`
- Premium-HTML-Mail-Templates → kommen aus `rb-wp-shared`
- Mega-Menus → kommen aus `rb-wp-shared`

Diese Komponenten landen im Composer-Paket `rb-hubs/rb-wp-shared` und werden per `composer require` eingebunden.

## Lizenz

GPL-2.0-or-later (WordPress-Standard).
