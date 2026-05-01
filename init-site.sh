#!/bin/bash
#
# rb-wp-starter Token-Replace
# Ersetzt alle {{TOKEN}} im Repo durch die übergebenen Werte.
# Löscht sich danach selbst.
#
# Usage:
#   ./init-site.sh SLUG PREFIX "NAME" "DESC" URL VERSION
#
# Beispiel:
#   ./init-site.sh meinprojekt mp "Mein Projekt" "WP-Theme für …" "https://meinprojekt.de" "1.0.0"

set -e

if [ "$#" -lt 6 ]; then
  echo "Usage: $0 SLUG PREFIX \"NAME\" \"DESC\" URL VERSION"
  echo "Beispiel: $0 meinprojekt mp \"Mein Projekt\" \"Beschreibung\" \"https://meinprojekt.de\" \"1.0.0\""
  exit 1
fi

SITE_SLUG="$1"
SITE_PREFIX="$2"
SITE_NAME="$3"
SITE_DESC="$4"
SITE_URL="$5"
SITE_VERSION="$6"
SITE_PREFIX_UC=$(echo "$SITE_PREFIX" | tr '[:lower:]' '[:upper:]')
SITE_AUTHOR="rb-hubs"

# Validierung
if [[ ! "$SITE_SLUG" =~ ^[a-z0-9-]+$ ]]; then
  echo "FEHLER: SLUG darf nur Kleinbuchstaben, Zahlen und Bindestrich enthalten."
  exit 1
fi
if [[ ! "$SITE_PREFIX" =~ ^[a-z]{2,4}$ ]]; then
  echo "FEHLER: PREFIX muss 2-4 Kleinbuchstaben sein (für Funktionsnamen)."
  exit 1
fi

echo "==> Token-Replace startet..."
echo "    SLUG:        $SITE_SLUG"
echo "    PREFIX:      $SITE_PREFIX (UC: $SITE_PREFIX_UC)"
echo "    NAME:        $SITE_NAME"
echo "    DESC:        $SITE_DESC"
echo "    URL:         $SITE_URL"
echo "    VERSION:     $SITE_VERSION"
echo ""

# Files-Liste: alle relevanten Files (PHP, HTML, JSON, MD, JS, CSS, YAML, sh)
# Schließt aus: node_modules, vendor, .git, init-site.sh selbst
FILES=$(find . -type f \
  \( -name "*.php" -o -name "*.html" -o -name "*.json" -o -name "*.md" \
     -o -name "*.js" -o -name "*.css" -o -name "*.yml" -o -name "*.yaml" \
     -o -name "*.sh" -o -name "*.xml" -o -name "*.neon" -o -name "*.dist" \
     -o -name ".gitignore" -o -name ".editorconfig" -o -name "CODEOWNERS" \
     -o -name "style.css" \) \
  -not -path "./node_modules/*" \
  -not -path "./vendor/*" \
  -not -path "./.git/*" \
  -not -name "init-site.sh")

# sed-Replace (BSD/GNU-kompatibel)
sed_inplace() {
  if [[ "$OSTYPE" == "darwin"* ]]; then
    sed -i '' "$@"
  else
    sed -i "$@"
  fi
}

for f in $FILES; do
  sed_inplace "s|{{SITE_SLUG}}|$SITE_SLUG|g" "$f"
  sed_inplace "s|{{SITE_PREFIX}}|$SITE_PREFIX|g" "$f"
  sed_inplace "s|{{SITE_PREFIX_UC}}|$SITE_PREFIX_UC|g" "$f"
  sed_inplace "s|{{SITE_NAME}}|$SITE_NAME|g" "$f"
  sed_inplace "s|{{SITE_DESC}}|$SITE_DESC|g" "$f"
  sed_inplace "s|{{SITE_URL}}|$SITE_URL|g" "$f"
  sed_inplace "s|{{SITE_VERSION}}|$SITE_VERSION|g" "$f"
  sed_inplace "s|{{SITE_AUTHOR}}|$SITE_AUTHOR|g" "$f"
done

echo "==> Token-Replace abgeschlossen."
echo ""
echo "==> Nächste Schritte:"
echo "    npm install"
echo "    composer install"
echo "    ddev start"
echo "    npm run build"
echo ""
echo "==> Lösche init-site.sh und INIT.md (Setup ist abgeschlossen)..."
rm -- "$0"
rm -f INIT.md
echo "Fertig."
