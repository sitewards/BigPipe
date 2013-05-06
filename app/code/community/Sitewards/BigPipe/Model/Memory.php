<?php
class Sitewards_BigPipe_Model_Memory {
	private $layout;
	public function setLayout(Mage_Core_Model_Layout $layout) {
		$this->layout = $layout;
	}

	private $bigPipes = array();

	/**
	 * adds a block to the memory
	 *
	 * @param SimpleXMLElement $node
	 * @param SimpleXMLElement $parent
	 * @throws Exception throws exception if block for node name does not exist
	 */
	public function add(SimpleXMLElement $node, SimpleXMLElement $parent) {
		$name = (string)$node['name'];
		$block = $this->layout->getBlock($name);
		if (!$block) {
			throw new Exception('block for node ' . $name . ' does not exist');
		}
		$block->setOriginalNode($node);
		$block->setParent($parent);
		$this->bigPipes[] = $block;
	}

	/**
	 * returns one block after another
	 *
	 * @return Sitewards_BigPipe_Block_Node
	 */
	public function getNextBigPipeBlock() {
		$bigPipeBlock = $this->shiftBlocks();
		$this->removeOldBlock($bigPipeBlock);
		return $bigPipeBlock;
	}

	/**
	 * shifts the blocks
	 *
	 * @return Sitewards_BigPipe_Block_Node
	 */
	private function shiftBlocks () {
		return array_shift($this->bigPipes);
	}

	/**
	 * removes a block from the layout
	 *
	 * @param Sitewards_BigPipe_Block_Node $bigPipeBlock
	 */
	private function removeOldBlock (Sitewards_BigPipe_Block_Node $bigPipeBlock) {
		// Remove old instantiated "sitewards_bigpipe/loading" block first
		$this->layout->removeBlock($bigPipeBlock->getNodeName());
	}

	/**
	 * returns true if there are still blocks in the queue
	 *
	 * @return bool
	 */
	public function hasBigPipeBlock () {
		return (count($this->bigPipes) > 0);
	}
}