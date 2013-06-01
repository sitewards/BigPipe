<?php
/**
 * block that will be rendered to start the bigpipe container
 *
 * @category    Sitewards
 * @package     Sitewards_BigPipe
 * @copyright   Copyright (c) Sitewards GmbH (http://www.sitewards.com)
 * @contact     magento@sitewards.com
 * @license     OSL-3.0
 */
class Sitewards_BigPipe_Block_Container_Start extends Mage_Core_Block_Template  {
	/**
	 * set the default template
	 */
	protected function _construct() {
		$this->setTemplate('sitewards/container/start.phtml');
		return parent::_construct();
	}

}