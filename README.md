# CodeSampleLangM

Your own language list for TinyMCE's code sample dialog.

A system plugin for Joomla 6 that sets the languages in the dropdown of TinyMCE's
"Insert/Edit code sample" dialog, e.g. SQL, Bash, Kotlin or PowerShell.
English and German included.

## Features

- Language list maintained in the plugin settings, as a table with add, remove and sort
- The list replaces TinyMCE's languages: add new ones, hide unused ones, change the order
- A fresh install shows TinyMCE's standard languages as a starting point
- Works for every TinyMCE editor in backend and frontend
- Updates via System → Update

## Requirements

- Joomla 6.0 or later
- PHP 8.3 or later
- Editor TinyMCE with the button "Code Sample" in its toolbar

## Installation

1. Download `plg_system_codesamplelang-<version>.zip` from the
   [latest release](https://github.com/SpeckenbachNet/CodeSampleLangM/releases/latest).
2. In Joomla: **System → Install → Extensions**, upload the file.
3. **System → Plugins → System - CodeSampleLangM**: edit the list, save, enable the plugin.

## Settings

| Column       | Meaning                                                       |
|--------------|---------------------------------------------------------------|
| Display name | Text in the dropdown, e.g. `Kotlin`                           |
| Prism value  | Identifier of the language in Prism, e.g. `kotlin`            |

The Prism values are listed at [prismjs.com](https://prismjs.com/#supported-languages)
(e.g. `sql`, `bash`, `kotlin`, `go`, `rust`, `powershell`, `docker`, `yaml`, `json`).
TinyMCE writes the value as `class="language-…"` into the `<code>` element.

As long as the list has never been saved, TinyMCE keeps its own languages.

## Good to know

- **Highlighting on the website:** the plugin only sets the dropdown. Colours on the
  website need Prism in your template (or a highlighting extension) with the
  languages you use. Inside the editor, TinyMCE highlights only the languages its own
  Prism copy knows; other languages show as plain text there.
- **Editors in modules:** a TinyMCE editor inside a module (e.g. a frontend form in a
  module position) keeps TinyMCE's languages. Editors in articles, custom modules,
  categories and other component forms get the list.

## Updates

Joomla checks this repository's releases for updates. New versions show up under
**System → Update → Extensions**.

## Licence

GNU General Public License version 2 or later.

Speckenbach.net · software@speckenbach.net · https://software.speckenbach.net
