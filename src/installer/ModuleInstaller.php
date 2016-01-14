<?php
namespace keeko\core\installer;

use Composer\IO\IOInterface;
use gossi\swagger\Swagger;
use keeko\core\events\ModuleEvent;
use keeko\core\model\Action;
use keeko\core\model\ActionQuery;
use keeko\core\model\Api;
use keeko\core\model\ApiQuery;
use keeko\core\model\Group;
use keeko\core\model\GroupQuery;
use keeko\core\model\Module;
use keeko\core\model\ModuleQuery;
use keeko\core\package\ModuleManager;
use keeko\core\schema\ModuleSchema;

class ModuleInstaller extends AbstractPackageInstaller {
	
	/** @var ModuleManager */
	private $manager;
	
	/** @var Group */
	private $guestGroup;
	
	/** @var Group */
	private $userGroup;
	
	/** @var Group */
	private $adminGroup;
	
	public function __construct() {
		parent::__construct();
		$this->manager = $this->service->getModuleManager();
	}

	public function install(IOInterface $io, $packageName) {
		$io->write('[Keeko] Install Module: ' . $packageName);
		
		$package = $this->getPackageSchema($packageName);
		$keeko = $package->getKeeko();
		
		if ($keeko->isModule()) {
			$pkg = $keeko->getModule();

			// create module
			$model = new Module();
			$model->setClassName($pkg->getClass());
			$model->setSlug($pkg->getSlug());
			$this->updatePackage($model, $pkg);
			
			// run module -> install
			$className = $pkg->getClass();
			$class = new $className($model, $this->service);
			$class->install();
			
			$this->dispatcher->dispatch(ModuleEvent::INSTALLED, new ModuleEvent($model));
		}
	}

	public function update(IOInterface $io, $packageName, $from, $to) {
		$io->write(sprintf('[Keeko] Update Module: %s from %s to %s', $packageName, $from, $to));
		
		// retrieve module
		$model = ModuleQuery::create()->findOneByName($packageName);
		$this->updatePackage($model, $packageName);
		
		// run module -> update
		$className = $model->getClass();
		$class = new $className($model, $this->service);
		$class->update($from, $to);
		
		// update api and actions
		if ($this->manager->isActivated($packageName)) {
			$this->updateModule($model);
		}
		
		$this->dispatcher->dispatch(ModuleEvent::UPDATED, new ModuleEvent($model));
	}

	public function uninstall(IOInterface $io, $packageName) {
		$io->write('[Keeko] Uninstall Module: ' . $packageName);

		// retrieve module
		$model = ModuleQuery::create()->findOneByName($packageName);

		// delete if found
		if ($model !== null) {
			$model->delete();
			
			// TODO: Check if api and actions are also deleted (by the call above)
		}

		$this->dispatcher->dispatch(ModuleEvent::UNINSTALLED, new ModuleEvent($model));
	}
	
	public function activate(IOInterface $io, $packageName) {
		$io->write('[Keeko] Activate Module: ' . $packageName);
		
		$package = $this->service->getPackageManager()->getComposerPackage($packageName);
		
		$model = ModuleQuery::create()->findOneByName($packageName);
		$model->setActivatedVersion($package->getPrettyVersion());
		$model->save();
		
		$this->updateModule($model);
	}
	
	private function updateModule(Module $model) {
		$package = $this->service->getPackageManager()->getPackage($model->getName());
		$keeko = $package->getKeeko();

		if ($keeko->isModule()) {
			$module = $keeko->getModule();
			$actions = $this->updateActions($model, $module);
			$this->updateApi($model, $module, $actions);
		}
	}
	
	private function updateActions(Module $model, ModuleSchema $module) {
		$actions = [];
	
		foreach ($module->getActionNames() as $name) {
			$action = $module->getAction($name);
			$a = new Action();
			$a->setName($name);
			$a->setModule($model);
			$a->setTitle($action->getTitle());
			$a->setDescription($action->getDescription());
			$a->setClassName($action->getClass());
				
			// add acl
			foreach ($action->getAcl() as $group) {
				$a->addGroup($this->getGroup($group));
			}
				
			$a->save();
			$actions[$name] = $a->getId();
		}
		
		// remove obsolete actions
		ActionQuery::create()
			->filterByModule($model)
			->where('Action.Name NOT IN ?', $module->getActionNames()->toArray())
			->delete();

		return $actions;
	}
	
	/**
	 * @param string $name
	 * @return Group
	 */
	private function getGroup($name) {
		switch ($name) {
			case 'guest':
				if ($this->guestGroup === null) {
					$this->guestGroup = GroupQuery::create()->filterByIsGuest(true)->findOne();
				}
				return $this->guestGroup;
	
			case 'user':
				if ($this->userGroup === null) {
					$this->userGroup = GroupQuery::create()->filterByIsDefault(true)->findOne();
				}
				return $this->userGroup;
	
			case 'admin':
				if ($this->adminGroup === null) {
					$this->adminGroup = GroupQuery::create()->findOneById(3);
				}
				return $this->adminGroup;
		}
	}
	
	private function updateApi(Module $model, $actions) {
		$repo = $this->service->getResourceRepository();
		$filename = sprintf('/packages/%s/api.json', $model->getName());
		if (!$repo->contains($filename)) {
			return;
		}

		// delete every api existent for the given module prior to create the new ones
// 		$q = ApiQuery::create()
// 			->useActionQuery()
// 				->filterByModule($model)
// 			->endUse();
		
// 		echo $q->toString();
		
// 		$q->delete();
	
		// TODO: Work out extension system before defining parent endpoints
		
// 		$swagger = new Swagger($repo->get($fileName)->getBody());

// 		foreach ($data['api']['apis'] as $apis) {
// 			$path = $apis['path'];
// 			foreach ($apis['operations'] as $op) {
// 				// fetch required params
// 				$required = [];
// 				if (isset($op['parameters'])) {
// 					foreach ($op['parameters'] as $param) {
// 						if (isset($param['paramType']) && $param['paramType'] == 'path') {
// 							$required[] = $param['name'];
// 						}
// 					}
// 				}
	
// 				// create record
// 				$fullPath = str_replace('//', '/', $base . '/' . $path);
// 				$api = new Api();
// 				$api->setMethod($op['method']);
// 				$api->setRoute($fullPath);
// 				$api->setActionId($actionMap[$op['nickname']]);
// 				$api->setRequiredParams(implode(',', $required));
// 				$api->save();
// 			}
// 		}
	
		$model->setApi(true);
		$model->save();
	}
	
	public function deactivate(IOInterface $io, $packageName) {
		$io->write('[Keeko] Deactivate Module: ' . $packageName);
		
		$mod = ModuleQuery::create()->filterByName($packageName)->findOne();
		$mod->setActivatedVersion(null);
		$mod->save();
	}
}