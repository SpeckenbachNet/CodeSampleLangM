<?php

/**
 * @package     Joomla.Plugin
 * @subpackage  System.CodeSampleLang
 *
 * @copyright   Copyright (C) 2026 Speckenbach.NET. All rights reserved.
 * @author      Michael Speckenbach
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Plugin\System\CodeSampleLang\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\Event;
use Joomla\Event\SubscriberInterface;

/**
 * Adds additional languages to TinyMCE "Insert code sample" dialog (codesample plugin, PRISM-based). 
 * The value must exactly match the Prism language identifier
 * (written as class="language-xxx" in the <code> tag).
 */
final class CodeSampleLang extends CMSPlugin implements SubscriberInterface
{
	/**
	 * Fallback list for cases where not a single row has yet been
	 * entered in the plugin backend (e.g., immediately after installation). Once the
	 * administrator populates the table in the backend, this list
	 * is completely ignored.
	 */
	private const DEFAULT_LANGUAGES = [
		['text' => 'HTML/XML',    'value' => 'markup'],
		['text' => 'JavaScript',  'value' => 'javascript'],
		['text' => 'CSS',         'value' => 'css'],
		['text' => 'PHP',         'value' => 'php'],
		['text' => 'Ruby',        'value' => 'ruby'],
		['text' => 'Python',      'value' => 'python'],
		['text' => 'Java',        'value' => 'java'],
		['text' => 'C',           'value' => 'c'],
		['text' => 'C#',          'value' => 'csharp'],
		['text' => 'C++',         'value' => 'cpp']
	];

	/**
	 * Reads the language list maintained in the plugin backend (subform field
	 * "languages") and converts it into the format expected by codesample_languages:
	 * [['text' => ..., 'value' => ...], ...]. 
	 * Falls back to DEFAULT_LANGUAGES if no rows have been defined yet.
	 *
	 * @return array<int, array{text: string, value: string}>
	 */
	private function getConfiguredLanguages(): array
	{
		$rows = $this->params->get('languages');

		if (empty($rows)) {
			return self::DEFAULT_LANGUAGES;
		}

		$languages = [];

		foreach ($rows as $row) {
			$row = (array) $row;

			$text  = trim((string) ($row['text'] ?? ''));
			$value = trim((string) ($row['value'] ?? ''));

			if ($text === '' || $value === '') {
				continue;
			}

			$languages[] = ['text' => $text, 'value' => $value];
		}

		return $languages !== [] ? $languages : self::DEFAULT_LANGUAGES;
	}

	/**
	 * @return array<string, string>
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			'onBeforeRender' => 'addCodesampleLanguages',
		];
	}

	public function addCodesampleLanguages(Event $event): void
	{
		$app = Factory::getApplication();

		// Relevant only in the backend (editor), but can also take effect in the frontend editor
		$doc = $app->getDocument();

		if (!method_exists($doc, 'getScriptOptions')) {
			return;
		}

		$options = $doc->getScriptOptions('plg_editor_tinymce');

		if (empty($options['tinyMCE']) || !is_array($options['tinyMCE'])) {
			return;
		}

		$languages = $this->getConfiguredLanguages();

		foreach ($options['tinyMCE'] as $editorId => $editorOptions) {
			$options['tinyMCE'][$editorId]['codesample_languages'] = $languages;
		}

		// false = completely overwrite instead of merging
		$doc->addScriptOptions('plg_editor_tinymce', $options, false);
	}
}
