<?php

class Sitewards_BigPipe_Block_Loading extends Mage_Core_Block_Template {
	/**
	 * set default template
	 */
	protected function _construct() {
		$this->setTemplate('sitewards/loading.phtml');
		return parent::_construct();
	}

}