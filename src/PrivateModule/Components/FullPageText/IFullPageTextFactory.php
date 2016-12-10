<?php

/**
 * @copyright   Copyright (c) 2016 Wunderman s.r.o. <wundermanprague@wunwork.cz>
 * @author      Petr Besir Horáček <sirbesir@gmail.com>
 * @package     FullPageText
 */

namespace Wunderman\CMS\FullPageText\PrivateModule\Components\FullPageText;

interface IFullPageTextFactory
{

	/**
	 * @return FullPageText
	 * @param  array $componentParams
	 */
	public function create(array $componentParams);

}