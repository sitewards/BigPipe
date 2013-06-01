<?php
/**
 * Rewrite of Core_Layout to replace bigpipe blocks with a Loading information
 *
 * @category    Sitewards
 * @package     Sitewards_BigPipe
 * @copyright   Copyright (c) Sitewards GmbH (http://www.sitewards.com)
 * @contact     magento@sitewards.com
 * @license     OSL-3.0
 */
class Sitewards_BigPipe_Model_Core_Layout extends Mage_Core_Model_Layout {
	/**
	 * Add block object to layout based on xml node data
	 *
	 * @param Varien_Simplexml_Element $node
	 * @param Varien_Simplexml_Element $parent
	 * @return Mage_Core_Model_Layout
	 */
	public function replaceBlock (SimpleXMLElement $node, SimpleXMLElement $parent) {
		$this->removeBlock((string)$node['name']);
		return $this->_generateBlock($node, $parent);
	}

	/**
	 * Create layout blocks hierarchy from layout xml configuration
	 * handles special bigpipe replacement
	 *
	 * @param Mage_Core_Layout_Element|null $parent
	 * @param boolean                       $parentIsBigPipe
	 */
	public function generateBlocks ($parent = null, $parentIsBigPipe = false) {
		if (empty($parent)) {
			$parent = $this->getNode();
		}
		foreach ($parent as $node) {
			$attributes = $node->attributes();
			if ((bool)$attributes->ignore) {
				continue;
			}
			switch ($node->getName()) {
				case 'block':
					$isBigPipe = ($parentIsBigPipe OR $node->attributes()->bigpipe);
					if ($isBigPipe) {
						$originalNode = $this->prepareNodeForBigPipe($node);
					}
					$this->_generateBlock($node, $parent);
					if ($isBigPipe) {
						$this->storeOriginalNode($originalNode, $parent);
					}
					$this->generateBlocks($node, $isBigPipe);
					break;

				case 'reference':
					$this->generateBlocks($node);
					break;

				case 'action':
					$this->_generateAction($node, $parent);
					break;
			}
		}
	}

	private function removeBlock ($blockName) {
		unset($this->_blocks[$blockName]);
	}

	/**
	 * updates the node to be replaced by a dummy block
	 *
	 * @param SimpleXMLElement $node
	 * @return SimpleXMLElement
	 */
	private function prepareNodeForBigPipe (SimpleXMLElement $node) {
		$originalNode = clone $node;
		unset($node['class']);
		$node['type'] = 'sitewards_bigpipe/wrapper';
		return $originalNode;
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
		$bigpipes->add($originalNode, $parent, $originalNode->attributes()->bigpipe);
	}
}