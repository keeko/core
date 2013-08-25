<?php
namespace keeko\core\module;

use keeko\core\entities\Module;
use keeko\core\entities\ActionQuery;
use keeko\core\entities\Action;
use keeko\core\exceptions\ModuleException;
use keeko\core\util\NameGenerator;
use keeko\core\application\Keeko;

abstract class AbstractModule implements ModuleInterface {

	/**
	 * @var Keeko
	 */
	protected $application;
	
	/**
	 * 
	 * @var Module
	 */
	protected $module;

	protected $actions;
	
	/* (non-PHPdoc)
	 * @see \keeko\core\module\ModuleInterface::setModule()
	 */
	public function setEntity(Module $module) {
		$this->module = $module;
		
		// load actions
		$this->loadActions();
	}
	
	public function getEntity() {
		return $this->module;
	}
	
	private function loadActions() {
		$this->actions = [];
		$actions = ActionQuery::create()->filterByModule($this->module)->find();
		
		/* @var $action Action */
		foreach ($actions as $action) {
			$this->actions[$action->getName()] = $action;
		}
	}

	/* (non-PHPdoc)
	 * @see \keeko\core\module\ModuleInterface::setApplication()
	 */
	public function setKeeko(Keeko $application) {
		$this->application = $application;
	}
	
	
	/* (non-PHPdoc)
	 * @see \keeko\core\module\ModuleInterface::getApplication()
	 */
	public function getKeeko() {
		return $this->application;
	}


	public function loadAction($name) {
// 		if (!array_key_exists($name, $this->actions)) {
// 			throw new ModuleException(sprintf('Action (%s) not found in Module (%s)', $name, $this->module->getName()));
// 		}

		$this->checkPermission($name);
	
		$className = NameGenerator::toStudlyCase($name);
		$namespace = $this->module->getName();
		$actionName = str_replace('/', '\\', sprintf('%s/action/%s', $namespace, $className));
	
		if (!class_exists($actionName)) {
			throw new ModuleException(sprintf('No Action (%s) found in Module (%s)', $name, $namespace));
		}
	
		return new $actionName();
	}
	
	
	public function loadController($name) {
// 		if (!array_key_exists($name, $this->actions)) {
// 			throw new ModuleException(sprintf('Action (%s) not found in Module (%s)', $name, $this->module->getName()));
// 		}

		$this->checkPermission($name);

		$appType = $this->application->getEntity()->getApplicationType()->getName();
		$className = NameGenerator::toStudlyCase($name);
		$namespace = $this->module->getName();
		$controllerName = str_replace('/', '\\', sprintf('/%s/controller/%s/%sController', $namespace, $appType, $className));
		
		if (!class_exists($controllerName)) {
			throw new ModuleException(sprintf('No Controller for Action (%s) in Module (%s) for Application Type (%s) found', $name, $namespace, $appType));
		}
		
		return new $controllerName();
	}
	
	/**
	 * Checks whether permission is given to access the given action
	 * 
	 * @param string $name
	 */
	private function checkPermission($name) {
		
	}
}
