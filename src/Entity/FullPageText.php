<?php

namespace Wunderman\CMS\FullPageText\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Petr Besir Horáček <sirbesir@gmail.com>
 * @ORM\Entity
 * @ORM\Table(name="extension_full_page_text")
 */
class FullPageText
{
	/**
     * @ORM\Id
	 * @ORM\Column(type="integer", nullable=false, options={ "unsigned": true })
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=150, nullable=false)
     */
    protected $linkText;

    /**
     * @ORM\Column(type="string", length=250, nullable=false)
     */
    protected $link;

    /**
     * @ORM\Column(type="string", length=7, nullable=false)
     */
    protected $bgColor;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    protected $content;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $status = 1;




	/**
	 * @return mixed
	 */
	public function getLinkText()
	{
		return $this->linkText;
	}

	/**
	 * @param mixed $linkText
	 * @return FullPageText
	 */
	public function setLinkText($linkText)
	{
		$this->linkText = $linkText;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getLink()
	{
		return $this->link;
	}

	/**
	 * @param mixed $link
	 * @return FullPageText
	 */
	public function setLink($link)
	{
		$this->link = $link;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getBgColor()
	{
		return $this->bgColor;
	}

	/**
	 * @param mixed $bgColor
	 * @return FullPageText
	 */
	public function setBgColor($bgColor)
	{
		$this->bgColor = $bgColor;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * @param mixed $content
	 * @return FullPageText
	 */
	public function setContent($content)
	{
		$this->content = $content;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * @param mixed $status
	 * @return FullPageText
	 */
	public function setStatus($status)
	{
		$this->status = $status;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

}
