<?php
/**
 * Dispatches bigpipe blocks which will be loaded after the first flush
 *
 * @category    Sitewards
 * @package     Sitewards_BigPipe
 * @copyright   Copyright (c) Sitewards GmbH (http://www.sitewards.com)
 * @contact     magento@sitewards.com
 * @license     OSL-3.0
 */
class Sitewards_BigPipe_Model_Dispatcher {
	protected function flush() {
		flush();
	}

	/**
	 * outputs the big pipe blocks
	 */
	public function outputBigPipeBlocks() {
		$this->flush();

		$memory = Mage::getSingleton('sitewards_bigpipe/memory');
		while ($memory->hasBigPipeBlock()) {
			$block = $memory->getNextBigPipeBlock();
			$originalBlock = $this->getGeneratedOriginalBlock($block);
			echo $this->getScriptOutput($block->getBigPipeId(), $originalBlock);

			$this->flush();
		}
	}

	/**
	 * returns the original block based on the current replacement block
	 *
	 * @param Sitewards_BigPipe_Block_Node $intermediateBlock
	 * @return Mage_Core_Block_Abstract
	 */
	private function getGeneratedOriginalBlock (Sitewards_BigPipe_Block_Node $intermediateBlock) {
		$blockcreator = Mage::getModel('sitewards_bigpipe/blockCreator');
		return $blockcreator->createOriginalBlock($intermediateBlock);
	}

	/**
	 * returns the script tag to replace the html element with original content
	 *
	 * @param int                      $bigPipeId
	 * @param Mage_Core_Block_Abstract $originalBlock
	 * @return string
	 */
	private function getScriptOutput ($bigPipeId, Mage_Core_Block_Abstract $originalBlock) {
		$block = Mage::app()->getLayout()->createBlock('sitewards_bigpipe/afterloadscript');
		$block->setBigPipeId($bigPipeId);
		$block->setOriginalBlock($originalBlock);
		return $block->toHtml();
	}



}