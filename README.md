# System - Code Sample Languages (plg_system_codesamplelang)

Erweitert die Sprachliste im TinyMCE **„Code-Beispiel einfügen“**-Dialog
(`codesample`-Plugin, PRISM-basiert) um zusätzliche Sprachen – bequem über
die Plugin-Einstellungen im Backend, ohne Codeänderung.

## Hintergrund / Problem

TinyMCE bringt im Code-Beispiel-Dialog von Haus aus nur 10 Sprachen mit:

HTML/XML, JavaScript, CSS, PHP, Ruby, Python, Java, C, C#, C++

Diese Liste (`codesample_languages`) lässt sich über die normale
Joomla-Backend-Oberfläche nicht erweitern. Dieses Plugin klinkt sich über
die offizielle Joomla-Document-API (`getScriptOptions` /
`addScriptOptions`) in das Event `onBeforeRender` ein und überschreibt die
Sprachliste – ohne Core-Dateien zu verändern.

## Voraussetzungen

- Joomla 5 oder 6 (TinyMCE 6, namespaced Plugins)
- Das Editor-Plugin **TinyMCE** muss aktiv sein
- Für die Frontend-Darstellung: das PRISM-Syntax-Highlighter-Plugin
  (Andre Hotzler) oder ein vergleichbares Prism.js-Setup

## Installation

1. `plg_system_codesamplelang.zip` unter
   **System → Verwalten → Erweiterungen installieren** hochladen.
2. Unter **System → Plugins** nach **„System - Code Sample Languages“**
   suchen und aktivieren.
3. Cache leeren (System-Cache und Browser-Cache).

## Bedienung

Unter **System → Plugins → System - Code Sample Languages** gibt es zwei
Einstellungen:

### 1. „Anzuzeigende Sprachen“

| Option | Ergebnis im Dropdown |
|---|---|
| Standardsprachen + zusätzliche Sprachen *(Standard)* | TinyMCE-Basis-10 **plus** die unten eingetragenen Sprachen |
| Nur die 10 eingebauten TinyMCE-Standardsprachen | Nur die Basis-10, Tabelle wird ignoriert |
| Nur die unten gelisteten zusätzlichen Sprachen | Nur das, was in der Tabelle steht |

### 2. „Sprachen“ (Tabelle)

Pro Zeile:

- **Anzeigename** – Text im Dropdown, z. B. `Kotlin`
- **Prism-Wert** – technische Kennung von
  [prismjs.com/#supported-languages](https://prismjs.com/#supported-languages),
  z. B. `kotlin`, `sql`, `bash`, `go`, `rust`, `powershell`, `docker`

Trägst du hier eine `value` ein, die es bereits in der TinyMCE-Basisliste
gibt (z. B. `php`), wird nur der Anzeigename überschrieben – es entsteht
kein doppelter Dropdown-Eintrag.

## Wichtig: Frontend-Darstellung

Damit eine neue Sprache im **Frontend** auch farblich hervorgehoben wird,
muss Prism.js die passende Sprachkomponente laden. Im PRISM-Plugin
(Andre Hotzler) empfiehlt sich dafür die Autoloader-Option **„all“**,
sonst bleiben neu hinzugefügte Sprachen im Frontend unformatiert, auch
wenn sie im Editor bereits auswählbar sind.

## Technische Details

- `services/provider.php` – Dependency-Injection-Registrierung (Joomla-4/5/6-Standard)
- `src/Extension/CodeSampleLang.php` – eigentliche Logik, hört auf `onBeforeRender`
- `script.php` – Installer-Skript, befüllt bei Erstinstallation optional Beispiel-Zusatzsprachen
- `language/en-GB/`, `language/de-DE/` – Backend-Übersetzungen (weitere Sprachen ergänzbar)

## Lizenz

GPL v2 oder später, siehe `LICENSE.txt`.

## Bekannte Grenzen

- Es lassen sich aktuell nur Sprachen **hinzufügen**/**anzeigen wählen**,
  nicht einzelne der 10 Basissprachen gezielt entfernen (dafür den Modus
  „Nur zusätzliche Sprachen“ nutzen und die gewünschten Basissprachen mit
  in die Tabelle eintragen).
- Getestet mit Joomla 6 / TinyMCE 6. Ältere Joomla-Versionen (TinyMCE ≤ 5)
  werden nicht unterstützt.
