<?php
/**
 * Memory of blocks which will be loaded via bigpipe
 *
 * @category    Sitewards
 * @package     Sitewards_BigPipe
 * @copyright   Copyright (c) Sitewards GmbH (http://www.sitewards.com)
 * @contact     magento@sitewards.com
 * @license     OSL-3.0
 */
class Sitewards_BigPipe_Model_Memory {
	private $layout;
	public function setLayout(Mage_Core_Model_Layout $layout) {
		$this->layout = $layout;
	}

	private $bigPipesOutput = array();
	private $bigPipesChildren = array();

	/**
	 * adds a block to the memory
	 *
	 * @param SimpleXMLElement $node
	 * @param SimpleXMLElement $parent
	 * @param boolean $output
	 * @throws Exception throws exception if block for node name does not exist
	 */
	public function add(SimpleXMLElement $node, SimpleXMLElement $parent, $output = false) {
		$name = (string)$node['name'];
		$block = $this->layout->getBlock($name);
		if (!$block) {
			throw new Exception('block for node ' . $name . ' does not exist');
		}
		$block->setOriginalNode(clone $node);
		$block->setParent(clone $parent);
		if ($output) {
			$this->bigPipesOutput[] = $block;
		} else {
			$this->bigPipesChildren[] = $block;
		}
	}

	/**
	 * returns one block after another
	 *
	 * @return Sitewards_BigPipe_Block_Node
	 */
	public function getNextBigPipeBlock() {
		$bigPipeBlock = $this->shiftBlocks();
		return $bigPipeBlock;
	}

	/**
	 * shifts the blocks
	 *
	 * @return Sitewards_BigPipe_Block_Node
	 */
	private function shiftBlocks () {
		return array_shift($this->bigPipesOutput);
	}

	/**
	 * returns true if there are still blocks in the queue
	 *
	 * @return bool
	 */
	public function hasBigPipeBlock () {
		return (count($this->bigPipesOutput) > 0);
	}
}