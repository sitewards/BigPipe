<?php
class Sitewards_BigPipe_Model_Core_Layout extends Mage_Core_Model_Layout {
	/**
	 * Add block object to layout based on xml node data
	 *
	 * @param Varien_Simplexml_Element $node
	 * @param Varien_Simplexml_Element $parent
	 * @return Mage_Core_Model_Layout
	 */
	public function generateOriginalBlock (SimpleXMLElement $node, SimpleXMLElement $parent) {
		return parent::_generateBlock($node, $parent);
	}

	/**
	 * handles special bigpipe replacement
	 *
	 * @param Varien_Simplexml_Element $node
	 * @param Varien_Simplexml_Element $parent
	 * @return Mage_Core_Model_Layout
	 */
	protected function _generateBlock ($node, $parent) {
		$originalNode = $this->prepareNodeForBigPipe($node);
		$return = parent::_generateBlock($node, $parent);
		if ($originalNode) {
			$this->storeOriginalNode($originalNode, $parent);
		}
		return $return;
	}

	public function removeBlock ($blockName) {
		unset($this->_blocks[$blockName]);
	}

	/**
	 * updates the node to be replaced by a dummy block
	 *
	 * @param SimpleXMLElement $node
	 * @return SimpleXMLElement
	 */
	private function prepareNodeForBigPipe (SimpleXMLElement $node) {
		if ($node->attributes()->bigpipe) {
			$originalNode = clone $node;
			unset($node['class']);
			$node['type'] = 'sitewards_bigpipe/loading';
			return $originalNode;
		}
	}

	/**
	 * stores the original node in memory to use it later after main content has been rendered
	 *
	 * @param SimpleXMLElement $originalNode
	 * @param SimpleXMLElement $parent
	 */
	private function storeOriginalNode (SimpleXMLElement $originalNode, SimpleXMLElement $parent) {
		$bigpipes = Mage::getSingleton('sitewards_bigpipe/memory');
		$bigpipes->setLayout($this);
		$bigpipes->add($originalNode, $parent);
	}
}