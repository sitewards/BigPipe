<?php
class Sitewards_BigPipe_Model_BlockTransfer {
	/**
	 * transfers information from source to target block
	 *
	 * @param $sourceBlock
	 * @param $targetBlock
	 */
	public function transfer($sourceBlock, $targetBlock) {
		$this->transferData($sourceBlock, $targetBlock);
		$this->transferCalledMethods($sourceBlock, $targetBlock);
	}

	/**
	 * transfers data entries from source to target block

	 * @param Varien_Object $sourceBlock
	 * @param Varien_Object $targetBlock
	 */
	private function transferData(Varien_Object $sourceBlock, Varien_Object $targetBlock) {
		foreach ($sourceBlock->getData() as $key => $value) {
			$targetBlock->setData($key, $value);
		}
	}

	/**
	 * transfers the called method from the source to target block
	 *
	 * @param Sitewards_BigPipe_Block_CallsCollector $sourceBlock
	 * @param Varien_Object                          $targetBlock
	 */
	private function transferCalledMethods(Sitewards_BigPipe_Block_CallsCollector $sourceBlock, Varien_Object $targetBlock) {
		foreach ($sourceBlock->getCalledMethods() as $calledMethod) {
			call_user_func_array(array($targetBlock, $calledMethod['method']), $calledMethod['args']);
		}

	}
}