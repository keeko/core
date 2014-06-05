<?php
namespace keeko\core\handler;

use keeko\core\handler\ContentHandlerInterface;

class ModuleActionHandler extends AbstractHandler {
	
	/*
	 * (non-PHPdoc) @see \keeko\core\handler\ContentHandlerInterface::getContents()
	 */
	public function getContents($match) {
		$module = $this->getModule($match);
		
		if (!array_key_exists('action', $match)) {
			$entity = $module->getEntity();
			$action = $entity->getDefaultAction();
		} else {
			$action = $match['action'];
		}
		
		/* @var $controller \keeko\core\action\ControllerInterface */
		$controller = $module->loadController($action);
		$controller->setParams($match['params']);
		
		return [
				'main' => $controller->run()
		];
	}
	
	/*
	 * (non-PHPdoc) @see \keeko\core\handler\ContentHandlerInterface::getLayout()
	 */
	public function getLayout($match) {
		return null;
	}
}