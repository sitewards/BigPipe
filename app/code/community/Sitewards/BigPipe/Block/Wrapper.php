<?php
/**
 * block that will be rendered while the original block is still being loaded
 *
 * @category    Sitewards
 * @package     Sitewards_BigPipe
 * @copyright   Copyright (c) Sitewards GmbH (http://www.sitewards.com)
 * @contact     magento@sitewards.com
 * @license     OSL-3.0
 */
class Sitewards_BigPipe_Block_Wrapper
	extends Mage_Core_Block_Text_List
	implements Sitewards_BigPipe_Block_CallsCollector, Sitewards_BigPipe_Block_Node {

	private $calledMethods = array();

	/**
	 * set default template
	 */
	protected function _construct() {
		return parent::_construct();
	}

	protected function _toHtml() {
		$html = '';
		// only output the loading for the top level bigpipe wrapper
		if ($this->hasDummyOutput()) {
			$loadingBlock = Mage::app()->getLayout()->createBlock('sitewards_bigpipe/loading');
			$loadingBlock->setElementId($this->getElementId());
			$html .= $loadingBlock->toHtml();
		}
		return $html;
	}

	/**
	 * should this block output the loading dialog
	 *
	 * @return bool
	 */
	private function hasDummyOutput() {
		return !($this->getParentBlock() instanceof Sitewards_BigPipe_Block_Wrapper);
	}

	/**
	 * collects all calls to this dummy block, so they can be later transferred to the original block
	 */
	public function __call($method, $args) {
		$this->calledMethods[] = array('method' => $method, 'args' => $args);
	}

	/**
	 * returns all called methods including arguments
	 *
	 * @return array
	 */
	public function getCalledMethods() {
		return $this->calledMethods;
	}

	/**
	 * returns the node name of this block
	 *
	 * @return string
	 */
	public function getNodeName() {
		return (string)$this->originalNode['name'];
	}

	/**
	 * returns element id for the div which will be later replaced via js
	 *
	 * @return string
	 */
	public function getElementId() {
		return 'bigpipe-' . $this->bigPipeId;
	}

	/**
	 * generates unique id for this block
	 *
	 * @return Mage_Core_Block_Abstract|void
	 */
	protected function _prepareLayout() {
		if ($this->hasDummyOutput()) {
			$this->bigPipeId = uniqid();
		}
		parent::_prepareLayout();
	}

	private $bigPipeId;
	public function getBigPipeId() {
		return $this->bigPipeId;
	}

	private $originalNode;
	public function setOriginalNode(SimpleXMLElement $node) {
		$this->originalNode = $node;
	}

	public function getOriginalNode() {
		return $this->originalNode;
	}

	private $parent;
	public function setParent(SimpleXMLElement $parent) {
		$this->parent = $parent;
	}

	public function getParent() {
		return $this->parent;
	}
}