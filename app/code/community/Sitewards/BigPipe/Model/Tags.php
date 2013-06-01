<?php
/**
 * handles manipulation of end tags </body> and </html> in response
 *
 * @category    Sitewards
 * @package     Sitewards_BigPipe
 * @copyright   Copyright (c) Sitewards GmbH (http://www.sitewards.com)
 * @contact     magento@sitewards.com
 * @license     OSL-3.0
 */
class Sitewards_BigPipe_Model_Tags {
	/**
	 * tag which should be cared of
	 *
	 * @var array
	 */
	protected $tags = array('body', 'html');

	/**
	 * strips the end tags "</body></html> from body
	 *
	 * @param Varien_Event_Observer $observer
	 */
	public function stripEndTags(Varien_Event_Observer $observer) {
		/* @var $response Zend_Controller_Response_Http */
		$response = $observer->getEvent()->getFront()->getResponse();
		$body = $response->getBody();
		foreach ($this->tags as $tag) {
			$body = str_replace($this->buildEndTag($tag), '', $body);
		}
		$response->setBody($body);
	}

	/**
	 * return correct closing tag structure for a tagname
	 *
	 * @param string $tag
	 * @return string
	 */
	private function buildEndTag($tag) {
		return '</' . $tag . '>';
	}

	/**
	 * returns the end tags as string
	 *
	 * @return string
	 */
	public function getEndTags() {
		return implode(
			'',
			array_map(
				function ($tag) {
					return $this->buildEndTag($tag);
				},
				$this->tags
			)
		);
	}
}