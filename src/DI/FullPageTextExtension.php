<?php
	/**
	 * @copyright   Copyright (c) 2016 Wunderman s.r.o. <wundermanprague@wunwork.cz>
	 * @author      Petr Besir Horáček <sirbesir@gmail.com>
	 * @package     FullPageText
	 */

	namespace Wunderman\CMS\FullPageText\DI;


	use Nette\DI\CompilerExtension;
	use Nette\Utils\Arrays;

	class FullPageTextExtension extends CompilerExtension
	{
		public function loadConfiguration()
		{
			$builder = $this->getContainerBuilder();
			$extensionConfig = $this->loadFromFile(__DIR__ . '/config.neon');
			$this->compiler->parseServices($builder, $extensionConfig, $this->name);

			$builder->parameters = Arrays::mergeTree($builder->parameters,
				Arrays::get($extensionConfig, 'parameters', []));
		}

		public function beforeCompile()
		{
			$builder = $this->getContainerBuilder();
			$builder->getDefinition('privateComposePresenter')->addSetup('addExtensionService',
				['fullPageText', $this->prefix('@privateModuleService')]);

			/**
			 * PublicModule component
			 */
			$builder->getDefinition('publicComposePresenter')->addSetup(
				'setComposeComponentFactory',
				['fullPageText', $this->prefix('@publicFullPageTextFactory')]
			);
			/**
			 * PrivateModule component
			 */
			$builder->getDefinition('privateComposePresenter')->addSetup(
				'setComposeComponentFactory',
				['fullPageText', $this->prefix('@privateFullPageTextFactory')]
			);
		}
	}
