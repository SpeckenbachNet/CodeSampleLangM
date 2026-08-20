<?php

/**
 * @package     Joomla.Plugin
 * @subpackage  System.CodeSampleLang
 *
 * @copyright   Copyright (C) 2026 Speckenbach.NET. All rights reserved.
 * @author      Michael Speckenbach
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Joomla\Plugin\System\CodeSampleLang\Extension\CodeSampleLang;

return new class () implements ServiceProviderInterface {
	public function register(Container $container): void
	{
		// Register the plugin in Joomla's dependency injection container.
		$container->set(
			PluginInterface::class,
			function (Container $container) {
				// Get the Joomla event dispatcher used by the plugin.
				$subject = $container->get(DispatcherInterface::class);

				// Load the plugin configuration from Joomla's plugin table.
				$config = (array) PluginHelper::getPlugin('system', 'codesamplelang');

				// Create the plugin instance with dispatcher and configuration.
				$plugin = new CodeSampleLang($subject, $config);

				// Provide the current Joomla application instance to the plugin.
				$plugin->setApplication(Factory::getApplication());

				// Return the fully initialized plugin instance.
				return $plugin;
			}
		);
	}
};