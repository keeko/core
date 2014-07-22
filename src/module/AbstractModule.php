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

abstract class AbstractModule {

	/**
	 *
	 * @var AbstractApplication
	 */
	protected $application;

	/**
	 *
	 * @var Module
	 */
	protected $model;

	protected $actions;

	/**
	 * @var PackageManager
	 */
	private $packageManager;

	/** @var AuthManager */
	protected $authManager;

	/** @var User */
	protected $user;

	public function __construct(Module $module, AbstractApplication $application) {
		$this->model = $module;
		$this->application = $application;
		$this->packageManager = $application->getPackageManager();
		$this->package = $this->packageManager->getModulePackage($module->getName());
		$this->authManager = $application->getAuthManager();
		$this->user = $this->authManager->getUser();
		
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
		
		if (isset($extra['keeko']) && isset($extra['keeko']['module']) && isset($extra['keeko']['module']['actions'])) {
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
	 * @param string $response
	 *        	the response type (e.g. html, json, ...)
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
		
		if (!(isset($this->actions[$name]) && isset($this->actions[$name]['response']) && isset($this->actions[$name]['response'][$response]))) {
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
		
		$class = new $className($action, $this, $response);
		
		return $class;
	}

	/**
	 * Checks whether permission is given to access the given action
	 *
	 * @param Action $action
	 */
	private function checkPermission(Action $action) {
		if (!$this->user->hasPermission($this->model->getId(), $action->getName())) {
// 			throw new PermissionDeniedException(sprintf('Action %s in module %s is forbidden', $action->getName(), $this->model->getName()));
		}
	}

	abstract public function install();

	abstract public function uninstall();

	abstract public function update($from, $to);
}