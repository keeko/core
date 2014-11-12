<?php
namespace keeko\core\module;

use keeko\core\exceptions\ModuleException;
use keeko\core\application\Keeko;
use keeko\core\model\Action;
use keeko\core\model\Module;
use keeko\core\model\ActionQuery;
use keeko\core\application\AbstractApplication;
use keeko\core\package\PackageManager;
use keeko\core\auth\AuthManager;
use keeko\core\model\User;
use keeko\core\exceptions\PermissionDeniedException;
use keeko\core\service\ServiceContainer;
use keeko\core\preferences\Preferences;

abstract class AbstractModule {
	
	/** @var Module */
	protected $model;

	protected $actions;

	/** @var User */
	protected $user;
	
	/** @var ServiceContainer */
	protected $service;
	
	protected $preferences;

	public function __construct(Module $module, ServiceContainer $service) {
		$this->model = $module;
		$this->service = $service;
		
		$packageManager = $service->getPackageManager();
		$this->package = $packageManager->getModulePackage($module->getName());
		
		$this->loadActions();
	}
	
	/**
	 * Returns the service container
	 *
	 * @return ServiceContainer
	 */
	public function getServiceContainer() {
		return $this->service;
	}

	/**
	 * Returns the module model
	 *
	 * @return Module
	 */
	public function getModel() {
		return $this->model;
	}
	
	/**
	 * Returns the module's preferences
	 *
	 * @return Preferences
	 */
	public function getPreferences() {
		if ($this->preferences === null) {
			$this->preferences = $this->service->getPreferenceLoader()->getModulePreferences($this->model->getId());
		}
		
		return $this->preferences();
	}

	private function loadActions() {
		$extra = $this->package->getExtra();
		
		if (isset($extra['keeko']) && isset($extra['keeko']['module']) && isset($extra['keeko']['module']['actions'])) {
			$this->actions = $extra['keeko']['module']['actions'];
		}
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
		
		$name = $action->getName();
		
		// check permission
		if (!$this->service->getFirewall()->canAccessAction($action)) {
			throw new PermissionDeniedException(sprintf('Can\'t access Action (%s) in Module (%s)', $name, $this->model->getName()));
		}
		
		// check if a response is given
		if (!(isset($this->actions[$name]) && isset($this->actions[$name]['response']) && isset($this->actions[$name]['response'][$response]))) {
			throw new ModuleException(sprintf('No Response (%s) given for Action (%s) in Module (%s)', $response, $name, $this->model->getName()));
		}
		$responseClass = $this->actions[$name]['response'][$response];
		
		if (!class_exists($responseClass)) {
			throw new ModuleException(sprintf('Response (%s) not found in Module (%s)', $responseClass, $this->model->getName()));
		}
		$response = new $responseClass($this, $response);
		
		// gets the action class
		$className = $action->getClassName();
		
		if (!class_exists($className)) {
			throw new ModuleException(sprintf('Action (%s) not found in Module (%s)', $className, $this->model->getName()));
		}
		
		$class = new $className($action, $this, $response);
		
		return $class;
	}

	abstract public function install();

	abstract public function uninstall();

	abstract public function update($from, $to);
}
