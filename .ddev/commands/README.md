# DDEV Custom Commands

Diese Scripts sind unter `ddev <command>` aufrufbar, sobald die Site läuft.

## Web-Commands (laufen im Container)

| Command | Was es macht |
|---|---|
| `ddev wp-fresh` | DB reset + WordPress clean install + Theme aktivieren |
| `ddev import-prod <dump.sql>` | Produktions-DB importieren + URLs auf lokal umschreiben |
| `ddev db-dump [name.sql]` | Lokale DB als SQL-Dump (Wrapper um `wp db export` mit Auto-Naming). DDEV hat selbst `ddev export-db` als Builtin. |

## Host-Commands (laufen auf deinem Mac)

| Command | Was es macht |
|---|---|
| `ddev tailwind-watch` | Tailwind im Watch-Mode starten (CSS rebuilds on save) |

## Kombi-Workflow für Migration einer Live-Site

```bash
# 1. Live-DB ziehen (außerhalb DDEV)
ssh user@server "wp db export -" > prod.sql

# 2. Lokal importieren
ddev import-prod prod.sql

# 3. Site läuft jetzt lokal mit Live-Daten
ddev launch
```

## Eigene Commands hinzufügen

Lege ein neues File unter `.ddev/commands/web/` (Container) oder `.ddev/commands/host/` (Mac) an, mache es ausführbar (`chmod +x`), Header sieht so aus:

```bash
#!/bin/bash
## Description: Was es macht (für ddev help)
## Usage: command-name [args]
## Example: ddev command-name beispiel
```
