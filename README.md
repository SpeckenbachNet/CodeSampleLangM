# System - Code Sample Languages (plg_system_codesamplelang)

Extends the language list in the TinyMCE **"Insert Code Sample"** dialog
(`codesample` plugin, PRISM-based) with additional languages – conveniently
via the plugin settings in the backend, without any code changes.

## Background / Problem

TinyMCE ships with only 10 languages by default in the code sample dialog:

HTML/XML, JavaScript, CSS, PHP, Ruby, Python, Java, C, C#, C++

This list (`codesample_languages`) cannot be extended through the normal
Joomla backend interface. This plugin hooks into the `onBeforeRender` event
via the official Joomla Document API (`getScriptOptions` /
`addScriptOptions`) and overrides the language list – without modifying
any core files.

## Requirements

- Joomla 5 or 6 (TinyMCE 6, namespaced plugins)
- The **TinyMCE** editor plugin must be active
- For frontend display: the PRISM syntax highlighter plugin
  (Andre Hotzler) or a comparable Prism.js setup

## Installation

1. Upload `plg_system_codesamplelang.zip` under
   **System → Manage → Install Extensions**.
2. Under **System → Plugins** search for **"System - Code Sample Languages"**
   and enable it.
3. Clear the cache (system cache and browser cache).

## Usage

Under **System → Plugins → System - Code Sample Languages** there are two
settings:

### 1. "Languages to Display"

| Option | Result in the dropdown |
|---|---|
| Default languages + additional languages *(Default)* | TinyMCE base 10 **plus** the languages entered below |
| Only the 10 built-in TinyMCE default languages | Only the base 10, table is ignored |
| Only the additional languages listed below | Only what's in the table |

### 2. "Languages" (table)

Per row:

- **Display name** – text shown in the dropdown, e.g. `Kotlin`
- **Prism value** – technical identifier from
  [prismjs.com/#supported-languages](https://prismjs.com/#supported-languages),
  e.g. `kotlin`, `sql`, `bash`, `go`, `rust`, `powershell`, `docker`

If you enter a `value` here that already exists in the TinyMCE base list
(e.g. `php`), only the display name is overwritten – no duplicate dropdown
entry is created.

## Important: Frontend Display

For a newly added language to also be syntax-highlighted in the
**frontend**, Prism.js needs to load the matching language component. In
the PRISM plugin (Andre Hotzler), the autoloader option **"all"** is
recommended for this – otherwise newly added languages will remain
unformatted in the frontend, even if they are already selectable in the
editor.

## Technical Details

- `services/provider.php` – dependency injection registration (Joomla 4/5/6 standard)
- `src/Extension/CodeSampleLang.php` – actual logic, listens to `onBeforeRender`
- `script.php` – installer script, optionally populates sample additional languages on first install
- `language/en-GB/`, `language/de-DE/` – backend translations (more languages can be added)

## License

GPL v2 or later, see `LICENSE.txt`.

## Known Limitations

- Currently, languages can only be **added**/**selected for display**,
  not individually removed from the 10 base languages (for that, use the
  "Only additional languages" mode and include the desired base languages
  in the table as well).
- Tested with Joomla 6 / TinyMCE 6. Older Joomla versions (TinyMCE ≤ 5)
  are not supported.
