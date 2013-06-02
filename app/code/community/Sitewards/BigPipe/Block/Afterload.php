<?php
/**
 * block that will be rendered to load generated content into do
 *
 * @category    Sitewards
 * @package     Sitewards_BigPipe
 * @copyright   Copyright (c) Sitewards GmbH (http://www.sitewards.com)
 * @contact     magento@sitewards.com
 * @license     OSL-3.0
 */
class Sitewards_BigPipe_Block_Afterload extends Mage_Core_Block_Template {
	private $bigPipeId;
	public function setBigPipeId($bigPipeId) {
		$this->bigPipeId = $bigPipeId;
	}

	/**
	 * set the default template
	 */
	protected function _construct() {
		$this->setTemplate('sitewards/bigpipe/afterload.phtml');
		return parent::_construct();
	}

	private $originalBlock;
	public function setOriginalBlock(Mage_Core_Block_Abstract $originalBlock) {
		$this->originalBlock = $originalBlock;
	}

	/**
	 * returns element id for the html element to change
	 * @return string
	 */
	public function getElementId() {
		return 'bigpipe-' . $this->bigPipeId;
	}

	/**
	 * return html content for replacement
	 * @return string
	 */
	public function getHtmlContent() {
		return $this->originalBlock->toHtml();
	}
}