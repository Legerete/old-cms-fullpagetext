<?php

/**
 * @copyright   Copyright (c) 2016 Wunderman s.r.o. <wundermanprague@wunwork.cz>
 * @author      Petr Besir Horáček <sirbesir@gmail.com>
 * @package     FullPageText
 */

namespace Wunderman\CMS\FullPageText\PrivateModule\Components\FullPageText;

use Kdyby\Doctrine\EntityManager;

class FullPageText extends \Wunderman\CMS\FullPageText\PublicModule\Components\FullPageText\FullPageText
{
	/**
	 * @var array
	 */
	protected $componentParams;

	public function __construct(array $componentParams = [], EntityManager $em)
	{
		parent::__construct($em);

		$this->componentParams = $componentParams;
	}

	/**
	 * Render setup
	 * @var integer $id
	 * @see Nette\Application\Control#render()
	 */
	public function render($id = null)
	{
		$params = [];
		foreach ($this->componentParams as $param)
		{
			$params[$param->getName()] = $param->getValue();
		}

		parent::render($params);
	}
}
