<?php
namespace keeko\core\handler;

use keeko\core\handler\ContentHandlerInterface;

class ModuleActionHandler implements ContentHandlerInterface {

	/* (non-PHPdoc)
	 * @see \keeko\core\handler\ContentHandlerInterface::getMainContent()
	*/
	public function getMainContent($match) { 
		 
	}
	
	/* (non-PHPdoc)
	 * @see \keeko\core\handler\ContentHandlerInterface::getAdditionalContent()
	 */
	public function getAdditionalContent($match) {
		return [];
	}

}