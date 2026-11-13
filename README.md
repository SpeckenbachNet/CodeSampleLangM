# CodeSampleLangM

Adds more programming languages to TinyMCE's code sample dialog.

A system plugin for Joomla 6 that adds languages to the dropdown of TinyMCE's
"Insert/Edit code sample" dialog, e.g. SQL, Bash, Kotlin or PowerShell.
English and German included.

## Features

- Additional languages maintained in the plugin settings, as a table with add, remove and sort
- Three modes: standard languages plus yours, only the standard languages, or only yours
- Rename a standard language (e.g. `PHP 8` for `php`) without a second entry
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
3. **System → Plugins → System - CodeSampleLangM**: add your languages, save, enable the plugin.

## Settings

### Languages to show

| Option                                        | Dropdown shows                                  |
|-----------------------------------------------|-------------------------------------------------|
| Standard languages plus additional languages *(default)* | TinyMCE's 10 languages **plus** the table |
| Only TinyMCE's 10 standard languages          | TinyMCE's 10 languages, the table is ignored    |
| Only the languages listed below               | only the languages from the table               |

TinyMCE's standard languages: HTML/XML, JavaScript, CSS, PHP, Ruby, Python, Java, C, C#, C++.

### Additional languages

| Column       | Meaning                                                       |
|--------------|---------------------------------------------------------------|
| Display name | Text in the dropdown, e.g. `Kotlin`                           |
| Prism value  | Identifier of the language in Prism, e.g. `kotlin`            |

A row with a standard value (e.g. `php`) only renames that entry; no second entry appears.

The Prism values are listed at [prismjs.com](https://prismjs.com/#supported-languages)
(e.g. `sql`, `bash`, `kotlin`, `go`, `rust`, `powershell`, `docker`, `yaml`, `json`).
TinyMCE writes the value as `class="language-…"` into the `<code>` element.

As long as the table is empty, TinyMCE keeps its own languages, whatever mode is set.

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
