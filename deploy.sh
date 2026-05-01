#!/bin/bash
#
# Deploy-Script — Tailwind build + git push.
# Hostinger zieht via Git-Integration automatisch (OAuth-Connect im hPanel).
#
# Usage:
#   ./deploy.sh "commit message"
#
# Bei großen Änderungen: erst lokal in DDEV testen, dann deployen.

set -e

MSG="${1:-update}"

echo "==> 1/4  Tailwind build (main + editor)..."
npm run build:all

echo "==> 2/4  git add..."
git add -A

echo "==> 3/4  git commit..."
git commit -m "$MSG" || echo "    (nichts zu committen, weiter)"

echo "==> 4/4  git push..."
git push

echo ""
echo "✅ Fertig. Live in ~30s (Hostinger pulled automatisch)."
echo "   Falls Cache hängt: LiteSpeed → Purge All."
