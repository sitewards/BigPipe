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

	/**
	 * @var Varien_Event_Observer
	 */
	protected $oObserver;

	protected function flush() {
		flush();
		ob_flush();
	}

	/**
	 * outputs the big pipe blocks
	 */
	public function outputBigPipeBlocks(Varien_Event_Observer $oObserver) {
		$this->oObserver = $oObserver;

		$this->checkConfig();
		echo $this->getContainerStart();

		$memory = Mage::getSingleton('sitewards_bigpipe/memory');
		$aOriginalBlocksToCache = array();
		while ($memory->hasBigPipeBlock()) {
			$block = $memory->getNextBigPipeBlock();
			$originalBlock = $this->getGeneratedOriginalBlock($block);
			$this->outputBlock($block->getBigPipeId(), $originalBlock);
			$aOriginalBlocksToCache[$block->getBigPipeId()] = $originalBlock;
		}
		$this->cacheBigPipe($aOriginalBlocksToCache);
		echo $this->getDocumentEnd();
	}

	/**
	 * Replace BigPipe markers in response objrct with original block html and
	 * save response in cache
	 *
	 * @param array $aBlocksToCache
	 */
	protected function cacheBigPipe(array $aBlocksToCache) {
		$oResponse = $this->oObserver->getEvent()->getFront()->getResponse();
		$sBody = $oResponse->getBody();
		foreach ($aBlocksToCache as $sBigPipeId => $oBlock) {
			$sStrToReplace = '<span id="bigpipe-' . $sBigPipeId . '">Loading ...</span>';
			$sBody = str_replace($sStrToReplace, $oBlock->toHtml(), $sBody);
		}
		$oResponse->setBody($sBody);
		if ($oPageCacheObserver = Mage::getModel('enterprise_pagecache/observer')) {
			$oPageCacheObserver->cacheResponse($this->oObserver);
		}
	}

	/**
	 * checks config settings required to flush single blocks properly
	 */
	private function checkConfig() {
		if (ini_get('zlib.output_compression') == 1) {
			Mage::log('Disable zlib.output_compression to use BigPipe blocks');
		}
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