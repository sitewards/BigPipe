<?php
interface Sitewards_BigPipe_Block_Node {
	public function getOriginalNode();
	public function getParent();
	public function getNodeName();
}