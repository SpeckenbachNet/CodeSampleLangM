<?php

/**
 * @package     CodeSampleLangM
 * @copyright   Speckenbach.net
 * @license     GNU General Public License version 2 or later
 */

namespace SpeckenbachNet\Plugin\System\CodeSampleLang\Extension;

\defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;

/**
 * Sets the language list of TinyMCE's "Insert/Edit code sample" dialog (codesample plugin, Prism based).
 *
 * Modes (setting "mode"):
 *   merge     TinyMCE's standard languages plus the languages from the table (default);
 *             a table row with a standard value only renames that entry
 *   standard  only TinyMCE's standard languages, the table is ignored
 *   custom    only the languages from the table
 *
 * Each value has to be a Prism language identifier; TinyMCE writes it as class="language-xxx" into the
 * <code> element.
 *
 * Runs in onBeforeRender: the component (and with it the editor) has been rendered and has stored its
 * options, the document itself has not been rendered yet. Editors inside modules are rendered later
 * and keep TinyMCE's list.
 */
final class CodeSampleLang extends CMSPlugin implements SubscriberInterface
{
    /** Script options key of the TinyMCE editor plugin */
    private const OPTIONS_KEY = 'plg_editor_tinymce';

    /** TinyMCE's built-in list (codesample plugin default), in TinyMCE's order */
    private const STANDARD_LANGUAGES = [
        ['text' => 'HTML/XML',   'value' => 'markup'],
        ['text' => 'JavaScript', 'value' => 'javascript'],
        ['text' => 'CSS',        'value' => 'css'],
        ['text' => 'PHP',        'value' => 'php'],
        ['text' => 'Ruby',       'value' => 'ruby'],
        ['text' => 'Python',     'value' => 'python'],
        ['text' => 'Java',       'value' => 'java'],
        ['text' => 'C',          'value' => 'c'],
        ['text' => 'C#',         'value' => 'csharp'],
        ['text' => 'C++',        'value' => 'cpp'],
    ];

    public static function getSubscribedEvents(): array
    {
        return [
            'onBeforeRender' => 'onBeforeRender',
        ];
    }

    public function onBeforeRender(): void
    {
        $doc = $this->getApplication()->getDocument();

        if ($doc === null || !method_exists($doc, 'getScriptOptions')) {
            return;
        }

        $options = $doc->getScriptOptions(self::OPTIONS_KEY);

        // No TinyMCE on this page
        if (empty($options['tinyMCE']) || !\is_array($options['tinyMCE'])) {
            return;
        }

        $languages = $this->getLanguages();

        // TinyMCE's own list is wanted: leave the options alone
        if ($languages === null) {
            return;
        }

        // 'default' holds the options shared by all editors, the other keys one editor each
        foreach (array_keys($options['tinyMCE']) as $editorId) {
            $options['tinyMCE'][$editorId]['codesample_languages'] = $languages;
        }

        // false = replace the options instead of merging them
        $doc->addScriptOptions(self::OPTIONS_KEY, $options, false);
    }

    /**
     * Builds the dropdown list for the selected mode.
     *
     * @return  array<int, array{text: string, value: string}>|null  null = keep TinyMCE's list
     */
    private function getLanguages(): ?array
    {
        $mode  = (string) $this->params->get('mode', 'merge');
        $table = $this->getTableLanguages();

        // An empty table never empties the dropdown
        if ($mode === 'standard' || $table === []) {
            return null;
        }

        if ($mode === 'custom') {
            return $table;
        }

        // merge: standard languages first, a table row with a standard value renames that entry
        $languages = [];

        foreach (self::STANDARD_LANGUAGES as $language) {
            $languages[$language['value']] = $language;
        }

        foreach ($table as $language) {
            $languages[$language['value']] = $language;
        }

        return array_values($languages);
    }

    /**
     * Reads the table (subform "languages") from the plugin settings.
     * Rows with an empty name or value are skipped, a repeated value keeps its last row.
     *
     * @return  array<int, array{text: string, value: string}>
     */
    private function getTableLanguages(): array
    {
        $rows = $this->params->get('languages');

        if (empty($rows) || (!\is_array($rows) && !\is_object($rows))) {
            return [];
        }

        $languages = [];

        foreach ($rows as $row) {
            $row   = (array) $row;
            $text  = trim((string) ($row['text'] ?? ''));
            $value = trim((string) ($row['value'] ?? ''));

            if ($text === '' || $value === '') {
                continue;
            }

            $languages[$value] = ['text' => $text, 'value' => $value];
        }

        return array_values($languages);
    }
}
