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
 * The list from the plugin settings replaces TinyMCE's built-in list. Each value has to be a Prism
 * language identifier; TinyMCE writes it as class="language-xxx" into the <code> element.
 *
 * Runs in onBeforeRender: the component (and with it the editor) has been rendered and has stored its
 * options, the document itself has not been rendered yet. Editors inside modules are rendered later
 * and keep TinyMCE's list.
 */
final class CodeSampleLang extends CMSPlugin implements SubscriberInterface
{
    /** Script options key of the TinyMCE editor plugin */
    private const OPTIONS_KEY = 'plg_editor_tinymce';

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

        $languages = $this->getConfiguredLanguages();

        // Nothing saved yet: keep TinyMCE's built-in list
        if ($languages === []) {
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
     * Reads the language list (subform "languages") from the plugin settings.
     * Rows with an empty name or value are skipped.
     *
     * @return  array<int, array{text: string, value: string}>
     */
    private function getConfiguredLanguages(): array
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

            $languages[] = ['text' => $text, 'value' => $value];
        }

        return $languages;
    }
}
