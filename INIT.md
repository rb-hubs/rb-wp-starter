# Site-Setup-Checkliste

Schritt-für-Schritt-Anleitung zum Aufsetzen einer neuen Site aus diesem Template.

## Voraussetzungen

- `gh` CLI eingerichtet und mit GitHub authentifiziert
- DDEV installiert (`brew install ddev/ddev/ddev`)
- Node 18+ (`brew install node`)
- Composer (`brew install composer`)
- Lokales Verzeichnis `~/Code/rb-hubs/` existiert (außerhalb iCloud!)

## Schritt 1 — Notion-DB-Eintrag

Lege in der Notion-DB "RB Sites" einen neuen Eintrag an mit:
- Slug (z.B. `meinprojekt`)
- Domain (z.B. `meinprojekt.de`)
- Brand (z.B. `rb-consulting` oder `client-xyz`)
- Status: `Bauen`

## Schritt 2 — Repo erstellen

```bash
gh repo create rb-hubs/rb-site-meinprojekt \
  --template rb-hubs/rb-wp-starter \
  --private \
  --description "WordPress-Site für Mein Projekt"
```

## Schritt 3 — Lokal klonen

```bash
cd ~/Code/rb-hubs
gh repo clone rb-hubs/rb-site-meinprojekt
cd rb-site-meinprojekt
```

## Schritt 4 — Token-Replace ausführen

```bash
./init-site.sh \
  meinprojekt \
  mp \
  "Mein Projekt" \
  "WordPress Block Theme für Mein Projekt" \
  "https://meinprojekt.de" \
  "1.0.0"
```

Argumente in dieser Reihenfolge:
1. `SITE_SLUG` (lowercase, no-spaces)
2. `SITE_PREFIX` (kurz, 2-4 Buchstaben, lowercase)
3. `SITE_NAME` (Display-Name, in Anführungszeichen)
4. `SITE_DESC` (in Anführungszeichen)
5. `SITE_URL` (mit https://)
6. `SITE_VERSION` (semver)

Das Script ersetzt alle `{{TOKEN}}` in Files und löscht sich danach selbst.

## Schritt 5 — Dependencies

```bash
npm install
composer install   # falls rb-wp-shared genutzt wird
```

## Schritt 6 — DDEV starten

```bash
ddev start
ddev launch       # öffnet Browser
```

WP-Admin-Setup:
- Username: `admin`
- Passwort: wird beim ersten Start angezeigt

## Schritt 7 — Tailwind build

```bash
npm run build:all   # main.css + editor.css
# oder im Watch-Mode während Entwicklung:
npm run dev
```

## Schritt 8 — Theme aktivieren & konfigurieren

Im WP-Admin:
1. Design → Themes → "Mein Projekt" aktivieren
2. Design → Customizer → Mein Projekt – Firmendaten → alle Felder ausfüllen
3. Design → Customizer → SMTP-Versand → konfigurieren (Passwort idealerweise per `wp-config.php`-Konstante)
4. Design → Customizer → Auto-Reply → Texte anpassen

## Schritt 9 — Erster Commit + Push

```bash
git add -A
git commit -m "Initial site setup für meinprojekt"
git push
```

## Schritt 10 — Hostinger-Deploy

Siehe Dokumentation unter `.github/workflows/deploy.yml`. Erst nach Setup von:
- Hostinger-Webhook-URL als GitHub-Secret `HOSTINGER_WEBHOOK_URL`
- Server-Pfad als GitHub-Secret `HOSTINGER_THEME_PATH`

Beim ersten Push auf `main` triggert der Workflow automatisch den Webhook.

## Troubleshooting

### "Plugin Pixfort hat Theme zerlegt"
LiteSpeed Cache → Purge All. Falls das nicht hilft: Pixfort deaktivieren.

### "Tailwind-Klassen werden nicht gerendert"
`npm run build` vergessen? Oder safelist in `tailwind.config.js` checken.

### "iCloud zerlegt mein Repo"
Du arbeitest im falschen Verzeichnis. `~/Code/rb-hubs/` ist außerhalb iCloud — dort sollte alles sein.

### "Lead-CPT zeigt keine Submissions"
Permalinks neu speichern (Einstellungen → Permalinks → Save). Custom Post Types brauchen das.
