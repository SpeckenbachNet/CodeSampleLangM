<?php

/**
 * @package     Joomla.Plugin
 * @subpackage  System.codesamplelang
 */

namespace Joomla\Plugin\System\CodeSampleLang\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\Event;
use Joomla\Event\SubscriberInterface;

/**
 * Fügt dem TinyMCE-Dialog "Code-Beispiel einfügen" (codesample-Plugin, PRISM-basiert)
 * zusätzliche Sprachen hinzu. Die value muss exakt der Prism-Sprachkennung entsprechen
 * (wird als class="language-xxx" in den <code>-Tag geschrieben).
 */
final class CodeSampleLang extends CMSPlugin implements SubscriberInterface
{
	/**
	 * Fallback-Liste, falls im Plugin-Backend noch keine einzige Zeile
	 * gepflegt wurde (z. B. direkt nach der Installation). Sobald der
	 * Administrator die Tabelle im Backend ausfüllt, wird diese hier
	 * komplett ignoriert.
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
	 * Liest die im Plugin-Backend gepflegte Sprachliste (Subform-Feld
	 * "languages") aus und wandelt sie in das von codesample_languages
	 * erwartete Format [['text' => ..., 'value' => ...], ...] um.
	 * Fällt auf DEFAULT_LANGUAGES zurück, wenn noch keine Zeile gepflegt ist.
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

		// Nur im Backend (Editor) relevant, kann aber auch im Frontend-Editor greifen
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

		// false = komplett überschreiben statt mergen
		$doc->addScriptOptions('plg_editor_tinymce', $options, false);
	}
}
