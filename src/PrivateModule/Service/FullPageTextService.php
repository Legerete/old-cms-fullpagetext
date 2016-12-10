<?php
/**
 * @copyright   Copyright (c) 2016 Wunderman s.r.o. <wundermanprague@wunwork.cz>
 * @author      Petr Besir Horáček <sirbesir@gmail.com>
 * @package     FullPageText
 */

namespace Wunderman\CMS\FullPageText\PrivateModule\Service;

use App\PrivateModule\AttachmentModule\Model\Service\Attachment;
use App\PrivateModule\PagesModule\Presenter\IExtensionService;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Form;
use Nette\Http\Request;
use Nette\Utils\ArrayHash;
use Nette\Utils\Arrays;
use App\Entity\ComposeArticleItemParam;
use Wunderman\CMS\FullPageText\Entity\FullPageText;

class FullPageTextService implements IExtensionService
{

	/**
	 * @var Request
	 */
	private $httpRequest;

	/**
	 * @type EntityManager
	 */
	private $em;

	/**
	 * FullPageTextService constructor.
	 *
	 * @param Request $httpRequest
	 * @param EntityManager $em
	 */
	public function __construct(Request $httpRequest, EntityManager $em)
	{
		$this->httpRequest = $httpRequest;
		$this->em = $em;
	}

	/**
	 * Prepare adding new item, add imputs to global form etc.
	 *
	 * @param Form $button
	 *
	 * @return mixed
	 */
	public function addItem(Form $form)
	{
		if(isset($form[self::ITEM_CONTAINER])) {
			unset($form[self::ITEM_CONTAINER]);
		}

		$item = $form->addContainer(self::ITEM_CONTAINER);
		$item->addText('link', 'Link');
		$item->addText('link_text', 'Link text');
		$item->addText('bg_color', 'Link text');
		$item->addText('anchor', 'Anchor')->setRequired('Anchor must be filled.');
		$item->addCheckbox('margin_top', 'Margin top')->setValue(1);
		$item->addCheckbox('margin_bottom', 'Margin bottom')->setValue(1);
		$item->addTextArea('content', 'Content');

		$item->addHidden('type')->setValue('fullPageText');
		$item->addHidden('itemId');
	}

	/**
	 * @param Form $form
	 *
	 * @return mixed
	 */
	public function editItemParams(Form $form, $editItem)
	{
		$params = $this->createParamsAssocArray($editItem->getParams());
		$item = $this->fullPageTextRepository()->find(Arrays::get($params, 'id', FALSE));

		if (! $item) {
			throw new \InvalidArgumentException("Item with id '{Arrays::get($params, 'id', false)}' not found.");
		}

		$this->addItem($form);

		$form[self::ITEM_CONTAINER]->setDefaults([
			'itemId' => $editItem->getId(),
			'alt' => Arrays::get($params, 'alt', NULL),
			'anchor' => Arrays::get($params, 'anchor', NULL),
			'link' => $item->getLink(),
			'link_text' => $item->getLinkText(),
			'bg_color' => $item->getBgColor(),
			'margin_top' => Arrays::get($params, 'margin_top', NULL),
			'margin_bottom' => Arrays::get($params, 'margin_bottom', NULL),
			'content' => $item->getContent(),
		]);
	}

	/**
	 * Make magic for creating new item, e.g. save new image and return his params for save.
	 * @var array $values Form values
	 * @return array Associated array in pair [ propertyName => value ] for store to the database
	 */
	public function processNew(Form $form, ArrayHash $values)
	{

		/** @var FullPageText $text */
		$text = new FullPageText();
		$text->setLink($values['link'])
			->setLinkText($values['link_text'])
			->setBgColor(str_replace('#', '',$values['bg_color']))
			->setContent($values['content']);

		$this->em->persist($text)->flush();

		return [
			'id' => $text->getId(),
			'margin_top' => isset($values['margin_top']) ? 1 : 0,
			'margin_bottom' => isset($values['margin_bottom']) ? 1 : 0,
			'anchor' => \Nette\Utils\Strings::webalize($values['anchor'])
		];
	}

	/**
	 * Editing current edited item
	 * @var array $values Form values
	 * @var array $itemParams
	 * @return array
	 */
	public function processEdit(Form $form, ArrayHash $values, $itemParams)
	{
		$textId = Arrays::get($itemParams, 'id', FALSE);
		if (! $textId) {
			throw new \InvalidArgumentException('Text Id not found in item params.');
		}

		$text = $this->fullPageTextRepository()->find($textId);
		if (! $text) {
			throw new \InvalidArgumentException("Text with id {$textId} not found.");
		}

		$marginTop = $this->composeArticleItemParamRepository()->findOneBy([
			'composeArticleItem' => $this->em->getReference(ComposeArticleItemParam::class,
				(int)$values['itemId']),
			'name' => 'margin_top'
		]);
		if (! $marginTop) {
			$marginTop = (new ComposeArticleItemParam())->setName('margin_top')->setComposeArticleItem($this->em->getReference(ComposeArticleItemParam::class,
				(int)$values['itemId']));
			$this->em->persist($marginTop);
		}
		$marginTop->setValue(isset($values['margin_top']) ? 1 : 0);

		$marginBottom = $this->composeArticleItemParamRepository()->findOneBy([
			'composeArticleItem' => $this->em->getReference(ComposeArticleItemParam::class,
				(int)$values['itemId']),
			'name' => 'margin_bottom'
		]);
		if (! $marginBottom) {
			$marginBottom = (new ComposeArticleItemParam())->setName('margin_bottom')->setComposeArticleItem($this->em->getReference(ComposeArticleItemParam::class,
				(int)$values['itemId']));
			$this->em->persist($marginBottom);
		}
		$marginBottom->setValue(isset($values['margin_bottom']) ? 1 : 0);

		$anchor = $this->composeArticleItemParamRepository()->findOneBy([
			'composeArticleItem' => $this->em->getReference(ComposeArticleItemParam::class,
				(int)$values['itemId']),
			'name' => 'anchor'
		]);
		if (! $anchor) {
			$anchor = (new ComposeArticleItemParam())->setName('anchor')->setComposeArticleItem($this->em->getReference(ComposeArticleItemParam::class,
				(int)$values['itemId']));
			$this->em->persist($marginBottom);
		}
		$anchor->setValue(\Nette\Utils\Strings::webalize($values['anchor']));


		$text->setLink($values['link'])->setLinkText($values['link_text'])->setBgColor(str_replace('#', '',
				$values['bg_color']))->setContent($values['content']);

		return [
			'margin_top' => isset($values['margin_top']) ? 1 : 0,
			'margin_bottom' => isset($values['margin_bottom']) ? 1 : 0,
			'anchor' => $values['anchor'],
		];
	}

	/**
	 * Compute anchor for item on the page
	 * @var object
	 * @return string
	 */
	public function getAnchor($item)
	{
		$params = $this->createParamsAssocArray($item->getParams());
		return isset($params['anchor']) ? $params['anchor'] : false;
	}

	/**
	 * @return string
	 */
	public function getAddItemTemplate()
	{
		return realpath(__DIR__ . '/../Templates/editItem.latte');
	}

	/**
	 * @return string
	 */
	public function getEditItemTemplate()
	{
		return $this->getAddItemTemplate();
	}


	/**
	 * @param $params
	 *
	 * @return array
	 */
	private function createParamsAssocArray($params)
	{
		$assocParams = [];
		foreach ($params as $param) {
			$assocParams[$param->getName()] = $param->getValue();
		}

		return $assocParams;
	}

	/**
	 * @return \Kdyby\Doctrine\EntityRepository
	 */
	private function fullPageTextRepository()
	{
		return $this->em->getRepository(\Wunderman\CMS\FullPageText\Entity\FullPageText::class);
	}

	/**
	 * @return \Kdyby\Doctrine\EntityRepository
	 */
	private function composeArticleItemParamRepository()
	{
		return $this->em->getRepository(ComposeArticleItemParam::class);
	}
}
