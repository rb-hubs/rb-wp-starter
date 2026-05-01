# Deploy auf Hostinger

Diese Anleitung läuft pro Site **einmal** beim Initial-Setup. Danach reicht `./deploy.sh "msg"` für jeden Push live.

## Architektur

```
[Lokal] npm build → git push → [GitHub] → [Hostinger Git-Integration] → git pull auf Server
```

Hostinger pulled selbst – wir triggern keinen Webhook aus GitHub Actions raus. Der **CI-Workflow** (`.github/workflows/ci.yml`) ist nur Quality-Gate (Lint + Build-Test), kein Deploy.

## Initial-Setup pro Site (einmalig)

### 1) Domain + Hosting in Hostinger anlegen

- hPanel → "Hostings" → Site auswählen
- Domain mappen / Subdomain anlegen falls neu

### 2) Git-Repository in Hostinger verbinden

- hPanel → "Erweitert" → "GitHub-Integration" (oder "Git")
- "Repository hinzufügen" → GitHub-OAuth durchklicken (einmalig pro Hostinger-Account)
- Repository auswählen: `rb-hubs/rb-site-MEINPROJEKT`
- Branch: `main`
- **Pfad auf Server (WICHTIG, Falle):**
  ```
  /home/u<HOSTING-ID>/domains/<DOMAIN>/public_html/wp-content/themes/<SITE-SLUG>
  ```
  NICHT `/public_html/...` ohne `domains/`-Pfad – dort wird die Domain nicht ausgeliefert.
- "Auto-deploy on push" aktivieren

### 3) WordPress installieren (falls neu)

- hPanel → "WordPress" → "WordPress installieren"
- ODER: über Hostinger one-click installer
- Theme im WP-Admin aktivieren (`<SITE-SLUG>`)

### 4) Customizer-Defaults setzen

- WP-Admin → Design → Customizer → "<SITE-NAME> – Firmendaten" → Felder ausfüllen
- Customizer → SMTP-Versand → Microsoft-365-Setup (Passwort idealerweise via wp-config-Konstante)

### 5) Erster Live-Push testen

```bash
cd ~/Code/rb-hubs/rb-site-MEINPROJEKT
./deploy.sh "feat: initial deploy test"
```

Im Browser nach ~30s prüfen ob die Site geht.

## Daily-Use nach Initial-Setup

```bash
./deploy.sh "fix: button color"
```

Das war's. Tailwind wird gebaut, Commit gemacht, gepusht. Hostinger pulled. Live.

## Cache-Falle

Wenn Änderungen nicht sichtbar werden:
- WP-Admin → LiteSpeed Cache → Purge All
- Inkognito-Fenster (Browser-Cache umgehen)
- `curl -sI <DOMAIN>/wp-content/themes/<SITE-SLUG>/assets/css/main.css` — `Last-Modified` checken

## Plugin-Warnung

Keine Theme-Framework-Plugins (Pixfort, Elementor-Pro vom Vorgänger-Theme, SeedProd) aktiv lassen — die injizieren CSS/HTML und zerlegen das Custom-Design.

## Staging-Branch (optional)

Wenn du eine Staging-Site willst:
- Subdomain `staging.<DOMAIN>` in Hostinger anlegen
- Zweite Git-Integration im hPanel auf gleiches Repo, aber Branch `staging`
- Pfad: `/home/u<ID>/domains/<DOMAIN>/public_html/staging/wp-content/themes/<SITE-SLUG>`
- Lokal: `git checkout staging && ./deploy.sh "test xyz"`

## Was CI im Detail prüft (vor Live-Schaden!)

- **PHP-Syntax** – hätte das heutige `T_DIR`-Drama im Browser verhindert
- **npm install + Tailwind build** – stellt sicher dass keine Tailwind-Klasse fehlt
- **Theme-Pflichtdateien** – style.css, theme.json, templates/index.html müssen da sein
- **Build-Size-Report** – sieht in Action-Output wenn CSS plötzlich 10x so groß wird

CI failed = Roter X-Marker auf dem Commit auf GitHub. **Hostinger pulled trotzdem** (wir blocken den Pull nicht, weil es eh schon zu spät wäre wenn der Push durch ist). CI ist Frühwarnsystem.

Wenn du strenger willst: Commits auf `main` nur via PR zulassen, und PR-Merge nur bei grüner CI. Dann ist CI ein Hard-Gate.
