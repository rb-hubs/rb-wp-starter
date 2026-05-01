<!--
PR-Template für rb-hubs Sites.
Hilft Code-Review fokussiert zu halten und nichts zu vergessen.
-->

## Was?

<!-- Eine Zeile: was ändert sich? -->

## Warum?

<!-- Kontext: welches Problem löst es / welcher Issue / welche Session -->

## Screenshots / Vorher-Nachher

<!-- Optional bei UI-Änderungen -->

## Checkliste

- [ ] Lokal in DDEV getestet (`ddev start && ddev launch`)
- [ ] Tailwind-Build läuft (`npm run build:all`)
- [ ] PHP-Lint clean (`composer lint` oder `find . -name '*.php' -exec php -l {} \;`)
- [ ] PHPCS läuft durch (`composer phpcs`)
- [ ] CI ist grün
- [ ] Bei Customizer-/CPT-Änderungen: WP-Admin neu testen, Permalinks geflusht
- [ ] DEPLOY.md / README angepasst falls Setup-relevant

## Risiko / Rollback

<!-- Wenn das fehlschlägt – wie rollback? Letzter known-good commit? -->
