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
		ob_flush();
	}

	/**
	 * outputs the big pipe blocks
	 */
	public function outputBigPipeBlocks() {
		echo $this->getContainerStart();

		$memory = Mage::getSingleton('sitewards_bigpipe/memory');
		while ($memory->hasBigPipeBlock()) {
			$block = $memory->getNextBigPipeBlock();
			$originalBlock = $this->getGeneratedOriginalBlock($block);
			$this->outputBlock($block->getBigPipeId(), $originalBlock);
		}

		echo $this->getDocumentEnd();
	}

	/**
	 * returns document html string end
	 *
	 * @return string
	 */
	private function getDocumentEnd() {
		$end = Mage::app()->getLayout()->createBlock('sitewards_bigpipe/container_end');
		$return = $end->toHtml();

		$tags = Mage::getModel('sitewards_bigpipe/tags');
		$return .= $tags->getEndTags();

		return $return;
	}

	/**
	 * returns start tag of bigpipe container
	 *
	 * @return string
	 */
	private function getContainerStart() {
		$this->flush();

		$start = Mage::app()->getLayout()->createBlock('sitewards_bigpipe/container_start');
		return $start->toHtml();
	}

	/**
	 * outputs one block
	 *
	 * @param int                      $bigPipeId
	 * @param Mage_Core_Block_Abstract $block
	 */
	private function outputBlock($bigPipeId, Mage_Core_Block_Abstract $block) {
		$output = $this->getOutput($bigPipeId, $block);
		if (Mage::getSingleton('sitewards_bigpipe/memory')->hasBigPipeBlock()) {
			$output .= $this->fillToBufferSize($output);
		}
		echo $output;
		$this->flush();
	}

	/**
	 * fills output with spaces until buffer size is reached
	 *
	 * @param string $output
	 * @return string
	 */
	private function fillToBufferSize($output) {
		$bufferSize = Mage::getStoreConfig('sitewards_bigpipe_config/sitewards_bigpipe_general/buffer_size');
		if (strlen($output) < $bufferSize) {
			return str_repeat(' ', $bufferSize - strlen($output));
		}
		return '';
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
	 * returns the html element with original content
	 *
	 * @param int                      $bigPipeId
	 * @param Mage_Core_Block_Abstract $originalBlock
	 * @return string
	 */
	private function getOutput ($bigPipeId, Mage_Core_Block_Abstract $originalBlock) {
		$block = Mage::app()->getLayout()->createBlock('sitewards_bigpipe/afterload');
		$block->setBigPipeId($bigPipeId);
		$block->setOriginalBlock($originalBlock);
		return $block->toHtml();
	}



}