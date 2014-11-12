<?php
namespace keeko\core\module;

use keeko\core\exceptions\ModuleException;
use keeko\core\package\PackageManager;
use keeko\core\application\AbstractApplication;
use keeko\core\model\ModuleQuery;
use keeko\core\model\Module;
use keeko\core\model\Action;
use keeko\core\installer\ModuleInstaller;
use keeko\core\model\Api;
use keeko\core\service\ServiceContainer;
use keeko\core\model\GroupQuery;

class ModuleManager {

	private $loadedModules = [];

	private $activatedModules = [];

	private $installedModules = [];
	
	/** @var ServiceContainer */
	private $service;

	public function __construct(ServiceContainer $service) {
		$this->service = $service;
		
		// load modules
		$modules = ModuleQuery::create()->find();
		
		foreach ($modules as $module) {
			if ($module->getActivatedVersion() !== null) {
				$this->activatedModules[$module->getName()] = $module;
			} else {
				$this->installedModules[$module->getName()] = $module;
			}
		}
	}
	
	// public function getInstalledModules() {
	// return $this->installedModules;
	// }
	
	// public function getActivatedModules() {
	// return $this->activatedModules;
	// }
	
	/**
	 * Loads a module and returns the associated class or returns if already loaded
	 *
	 * @param String $packageName
	 * @throws ModuleException
	 * @return AbstractModule
	 */
	public function load($packageName) {
		if (array_key_exists($packageName, $this->loadedModules)) {
			return $this->loadedModules[$packageName];
		}
		
		// check activation
		if (!array_key_exists($packageName, $this->activatedModules)) {
			throw new ModuleException(sprintf('Module (%s) not activated', $packageName), 501);
		}
		
		$model = $this->activatedModules[$packageName];
		
		if ($model->getInstalledVersion() > $model->getActivatedVersion()) {
			throw new ModuleException(sprintf('Module Version Mismatch (%s). Module needs updated by the Administrator', $packageName), 500);
		}
		
		// load
		$className = $model->getClassName();
		
		/* @var $mod AbstractModule */
		$mod = new $className($model, $this->service);
		$this->loadedModules[$packageName] = $mod;
		
		return $mod;
	}

	public function activate($packageName) {
		// already activated ?
		if (isset($this->activatedModules[$packageName])) {
			return;
		}
		
		$module = ModuleQuery::create()->findOneByName($packageName);
		if ($module === null) {
			throw new ModuleException(sprintf('Module (%s) not installed for activation', $packageName));
		}
		$module->setActivatedVersion($module->getInstalledVersion());
		$module->save();
		$package = $this->service->getPackageManager()->getModulePackage($packageName);
		
		// install actions
		$extra = $package->getExtra();
		if (isset($extra['keeko']) && isset($extra['keeko']['module'])) {
			$actions = $this->installActions($module, $extra['keeko']['module']);
			$this->installApi($module, $extra['keeko']['module'], $actions);
		}
	}

	private function installActions(Module $module, $data) {
		if (!isset($data['actions'])) {
			return;
		}
		
		$actions = [];
		
		foreach ($data['actions'] as $name => $options) {
			$a = new Action();
			$a->setName($name);
			$a->setModule($module);
			
			if (isset($options['title'])) {
				$a->setTitle($options['title']);
			}
			
			if (isset($options['description'])) {
				$a->setDescription($options['description']);
			}
			
			if (isset($options['class'])) {
				$a->setClassName($options['class']);
			}
			
			// add acl
			if (isset($options['acl'])) {
				foreach ($options['acl'] as $group) {
					$a->addGroup($this->getGroup($group));
				}
			}
			
			$a->save();
			$actions[$name] = $a->getId();
		}
		
		return $actions;
	}
	
	private function getGroup($name) {
		switch ($name) {
			case 'guest':
				return GroupQuery::create()->filterByIsGuest(true)->findOne();
				break;
				
			case 'user':
				return GroupQuery::create()->filterByIsDefault(true)->findOne();
				break;
				
			case 'admin':
				return GroupQuery::create()->findOneById(3);
				break;
		}
	}

	private function installApi(Module $module, $data, $actionMap) {
		if (!isset($data['api'])) {
			return;
		}
		
		if (!isset($data['api']['apis'])) {
			return;
		}
		
		$base = '/';
		if (isset($data['api']['resourcePath'])) {
			$base = $data['api']['resourcePath'];
		}
		
		foreach ($data['api']['apis'] as $apis) {
			$path = $apis['path'];
			foreach ($apis['operations'] as $op) {
				// fetch required params
				$required = [];
				if (isset($op['parameters'])) {
					foreach ($op['parameters'] as $param) {
						if (isset($param['paramType']) && $param['paramType'] == 'path') {
							$required[] = $param['name'];
						}
					}
				}
				
				// create record
				$fullPath = str_replace('//', '/', $base . '/' . $path);
				$api = new Api();
				$api->setMethod($op['method']);
				$api->setRoute($fullPath);
				$api->setActionId($actionMap[$op['nickname']]);
				$api->setRequiredParams(implode(',', $required));
				$api->save();
			}
		}
		
		$module->setApi(true);
		$module->save();
	}

	public function deactivate($packageName) {
		if (array_key_exists($packageName, $this->activatedModules) && !array_key_exists($packageName, $this->installedModules)) {
			
			$mod = ModuleQuery::create()->filterByName($packageName)->findOne();
			$mod->setActivatedVersion(null);
			$mod->save();
			
			unset($this->activatedModules[$packageName]);
		}
	}
	
	// /**
	// * Returns wether a module was loaded
	// *
	// * @param String $packageName
	// * @return boolean true if loaded, false if not
	// */
	// public function isLoaded($packageName) {
	// return array_key_exists($packageName, $this->loadedModules);
	// }
}
