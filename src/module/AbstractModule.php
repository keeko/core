<?php
namespace keeko\core\module;

use keeko\core\exceptions\ModuleException;
use keeko\core\application\Keeko;
use keeko\core\model\Action;
use keeko\core\model\Module;
use keeko\core\model\ActionQuery;
use keeko\core\application\AbstractApplication;

abstract class AbstractModule {

	/**
	 * @var Keeko
	 */
	protected $application;
	
	/**
	 *
	 * @var Module
	 */
	protected $model;

	protected $actions;

	private $packageManager;

	public function __construct(Module $module, AbstractApplication $application) {
		$this->model = $module;
		$this->application = $application;
		$this->packageManager = $application->getPackageManager();
		$this->package = $this->packageManager->getModulePackage($module->getName());
		
		$this->loadActions();
	}
	
	/**
	 * Returns the module model
	 *
	 * @return Module
	 */
	public function getModel() {
		return $this->model;
	}
	
	private function loadActions() {
		$extra = $this->package->getExtra();
		
		if (isset($extra['keeko']) && isset($extra['keeko']['module'])
				&& isset($extra['keeko']['module']['actions'])) {
			$this->actions = $extra['keeko']['module']['actions'];
		}
	}

	public function setApplication(AbstractApplication $application) {
		$this->application = $application;
	}
	
	
	/**
	 * Returns the application
	 *
	 * @return AbstractApplication
	 */
	public function getApplication() {
		return $this->application;
	}


	/**
	 * Loads the given action
	 *
	 * @param Action|string $name
	 * @param string $response the response type (e.g. html, json, ...)
	 * @return AbstractAction
	 */
	public function loadAction($nameOrAction, $response) {
		
		if ($nameOrAction instanceof Action) {
			$action = $nameOrAction;
		} else {
			$action = ActionQuery::create()->filterByModule($this->model)->findOneByName($nameOrAction);
				
			if ($action === null) {
				throw new ModuleException(sprintf('Action (%s) not found in Module (%s)', $nameOrAction, $this->model->getName()));
			}
		}
		
		$this->checkPermission($action);
		
		$name = $action->getName();
		
		if (!(isset($this->actions[$name])
				&& isset($this->actions[$name]['response'])
				&& isset($this->actions[$name]['response'][$response]))) {
			throw new ModuleException(sprintf('No Response (%s) given for Action (%s) in Module (%s)', $response, $name, $this->model->getName()));
		}
		$responseClass = $this->actions[$name]['response'][$response];
		
		if (!class_exists($responseClass)) {
			throw new ModuleException(sprintf('Response (%s) not found in Module (%s)', $responseClass, $this->model->getName()));
		}
		$response = new $responseClass($this, $response);
		

		$className = $action->getClassName();
	
		if (!class_exists($className)) {
			throw new ModuleException(sprintf('Action (%s) not found in Module (%s)', $className, $this->model->getName()));
		}
	
		$class = new $className($action, $response);
		
		return $class;
	}
	
	/**
	 * Checks whether permission is given to access the given action
	 *
	 * @param Action $action
	 */
	private function checkPermission($action) {
		
	}
	
	abstract public function install();
	
	abstract public function uninstall();
	
	abstract public function update($from, $to);
}
