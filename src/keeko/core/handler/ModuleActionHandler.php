<?php
namespace keeko\core\handler;

use keeko\core\handler\ContentHandlerInterface;

class ModuleActionHandler implements ContentHandlerInterface {

	/* (non-PHPdoc)
	 * @see \keeko\core\handler\ContentHandlerInterface::getAdditionalContent()
	 */
	public function getContents($match) {
		return ['main' => 'Hello World'];
	}

	
	/* (non-PHPdoc)
	 * @see \keeko\core\handler\ContentHandlerInterface::getLayout()
	 */
	public function getLayout($match) {
		return null;
	}

}