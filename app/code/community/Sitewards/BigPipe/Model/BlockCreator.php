<?php
/**
 * Creates the original block based on a temporary replacement
 *
 * @category    Sitewards
 * @package     Sitewards_BigPipe
 * @copyright   Copyright (c) Sitewards GmbH (http://www.sitewards.com)
 * @contact     magento@sitewards.com
 * @license     OSL-3.0
 */
class Sitewards_BigPipe_Model_BlockCreator {
	/**
	 * creates the original block which will be replaced into the content
	 *
	 * @param Sitewards_BigPipe_Block_Node $sourceBlock
	 * @return Mage_Core_Block_Abstract
	 */
	public function createOriginalBlock(Sitewards_BigPipe_Block_Node $sourceBlock) {
		$block = $this->generateBlock($sourceBlock);
		$this->transferFromBigPipeBlock($sourceBlock, $block);
		$sourceChildBlocks = $sourceBlock->getSortedChildBlocks();
		foreach ($sourceChildBlocks as $sourceChildBlock) {
			if ($sourceChildBlock instanceof Sitewards_BigPipe_Block_Node) {
				$this->createOriginalBlock($sourceChildBlock);
			}
		}
		return $block;
	}

	/**
	 * generates the original block
	 *
	 * @param Sitewards_BigPipe_Block_Node $sourceBlock
	 * @return Mage_Core_Block_Abstract
	 */
	private function generateBlock(Sitewards_BigPipe_Block_Node $sourceBlock) {
		/* @var $node SimpleXMLElement */
		$node = $sourceBlock->getOriginalNode();
		$layout = Mage::app()->getLayout();
		$nodeName = $sourceBlock->getNodeName();

		$layout->replaceBlock($node, $sourceBlock->getParent());

		return $layout->getBlock($nodeName);
	}

	/**
	 * transfers data from the current block replacement to the original block
	 *
	 * @param Varien_Object $bigPipeBlock
	 * @param Varien_Object $block
	 */
	private function transferFromBigPipeBlock(Varien_Object $bigPipeBlock, Varien_Object $block) {
		$blocktransfer = Mage::getModel('sitewards_bigpipe/blockTransfer');
		$blocktransfer->transfer($bigPipeBlock, $block);

	}

}