<?php

/**
 * @package     Joomla.Plugin
 * @subpackage  System.codesamplelang
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
		$container->set(
			PluginInterface::class,
			function (Container $container) {
				$subject = $container->get(DispatcherInterface::class);
				$config  = (array) PluginHelper::getPlugin('system', 'codesamplelang');

				$plugin = new CodeSampleLang($subject, $config);
				$plugin->setApplication(Factory::getApplication());

				return $plugin;
			}
		);
	}
};
