<?php
class Sitewards_BigPipe_Block_Afterloadscript extends Mage_Core_Block_Template {
	private $bigPipeId;
	public function setBigPipeId($bigPipeId) {
		$this->bigPipeId = $bigPipeId;
	}

	/**
	 * set the default template
	 */
	protected function _construct() {
		$this->setTemplate('sitewards/afterload.phtml');
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