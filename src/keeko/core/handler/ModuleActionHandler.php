<?php
namespace keeko\core\handler;

use keeko\core\handler\ContentHandlerInterface;

class ModuleActionHandler extends AbstractHandler {

	/* (non-PHPdoc)
	 * @see \keeko\core\handler\ContentHandlerInterface::getAdditionalContent()
	 */
	public function getContents($match) {
		$mm = $this->keeko->getModuleManager();
		$module = $mm->load($match['module']);
		
		if (!array_key_exists('action', $match)) {
			$entity = $module->getEntity();
			$action = $entity->getDefaultAction();
		} else {
			$action = $match['action'];
		}
		
		$controller = $module->loadController($action);
		
		return ['main' => $controller->run()];
	}
	
	/* (non-PHPdoc)
	 * @see \keeko\core\handler\ContentHandlerInterface::getLayout()
	 */
	public function getLayout($match) {
		return null;
	}

}