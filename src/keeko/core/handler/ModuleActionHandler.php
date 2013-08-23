<?php
namespace keeko\core\handler;

use keeko\core\handler\ContentHandlerInterface;

class ModuleActionHandler implements ContentHandlerInterface {

	/* (non-PHPdoc)
	 * @see \keeko\core\handler\ContentHandlerInterface::getAdditionalContent()
	 */
	public function getContents($match) {
		return ['main' => print_r($match, true)];
	}

	
	/* (non-PHPdoc)
	 * @see \keeko\core\handler\ContentHandlerInterface::getLayout()
	 */
	public function getLayout($match) {
		return null;
	}

}