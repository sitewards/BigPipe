<?php
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
		return $block;
	}

	/**
	 * generates the original block
	 *
	 * @param Sitewards_BigPipe_Block_Node $sourceBlock
	 * @return Mage_Core_Block_Abstract
	 */
	private function generateBlock(Sitewards_BigPipe_Block_Node $sourceBlock) {
		$node = $sourceBlock->getOriginalNode();
		$layout = Mage::app()->getLayout();

		$layout->generateOriginalBlock($node, $sourceBlock->getParent());
		$layout->generateBlocks($node);

		return  $layout->getBlock($sourceBlock->getNodeName());
	}

	/**
	 * transfers data from the current block replacement to the original block
	 *
	 * @param Varien_Object $bigPipeBlock
	 * @param Varien_Object $block
	 */
	private function transferFromBigPipeBlock(Varien_Object $bigPipeBlock, Varien_Object $block) {
		$blocktransfer = Mage::getModel('sitewards_bigpipe/blocktransfer');
		$blocktransfer->transfer($bigPipeBlock, $block);

	}

}