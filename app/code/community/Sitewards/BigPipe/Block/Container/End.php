<?php
/**
 * block that will be rendered to end the bigpipe container
 *
 * @category    Sitewards
 * @package     Sitewards_BigPipe
 * @copyright   Copyright (c) Sitewards GmbH (http://www.sitewards.com)
 * @contact     magento@sitewards.com
 * @license     OSL-3.0
 */
class Sitewards_BigPipe_Block_Container_End extends Mage_Core_Block_Template  {
	/**
	 * set the default template
	 */
	protected function _construct() {
		$this->setTemplate('sitewards/bigpipe/container/end.phtml');
		return parent::_construct();
	}

}