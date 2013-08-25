<?php
namespace keeko\core\module;

use Symfony\Component\Finder\Finder;
use keeko\core\exceptions\ModuleException;
use keeko\core\entities\ModuleQuery;
use keeko\core\PackageManager;
use keeko\core\entities\Action;
use keeko\core\application\Keeko;
use keeko\core\entities\Module;

class ModuleManager {

	/**
	 * 
	 * @var Keeko
	 */
	private $keeko;
	private $loadedModules = [];
	private $activatedModules = [];
	private $installedModules = [];
	
	private $packageManager;
	
// 	private $classLoader;
// 	public function __construct(ClassLoader $classLoader) {
// 		$this->classLoader = $classLoader;

// 		$finder = new Finder();
// 		$result = $finder->directories()->in(KEEKO_PATH_MODULES)->ignoreDotFiles(true);

// 		foreach ($result as $unixname) {
// 			$this->availableModules []= $unixname->getFilename();
// 		}
// 	}
	
	public function __construct(Keeko $keeko = null) {
		$modules = ModuleQuery::create()->find();
		
		foreach ($modules as $module) {
			if ($module->getActivatedVersion() !== null) {
				$this->activatedModules[$module->getName()] = $module;
			} else {
				$this->installedModules[$module->getName()] = $module;
			}
		}
		
		$this->packageManager = new PackageManager();
		$this->keeko = $keeko;
	}

// 	public function getInstalledModules() {
// 		return $this->installedModules;
// 	}
	
// 	public function getActivatedModules() {
// 		return $this->activatedModules;
// 	}

	/**
	 * Loads a module and returns the associated class or returns if already loaded
	 * 
	 * @param String $packageName
	 * @throws ModuleException
	 * @return ModuleInterface
	 */
	public function load($packageName) {
		if (array_key_exists($packageName, $this->loadedModules)) {
			return $this->loadedModules[$packageName];
		}
		
		// check activation
		if (!array_key_exists($packageName, $this->activatedModules)) {
			throw new ModuleException(sprintf('Module (%s) not activated', $packageName), 501);
		}
		
		$entity = $this->activatedModules[$packageName];

		// check environment
// 		$descriptor = $this->getModuleDescriptor($packageName);
// 		if ($descriptor->getApiVersion() != KEEKO_API) {
// 			throw new ModuleException(sprintf('API Version Mismatch (%s)', $packageName), 500);
// 		}


		if ($entity->getInstalledVersion() > $entity->getActivatedVersion()) {
			throw new ModuleException(sprintf('Module Version Mismatch (%s). Module needs updated by the Administrator', $packageName), 500);
		}

		// load
		$className = $entity->getClassName();
// 		$namespace = implode('\\', array_slice(explode('\\', $module->getClassname()), 0, -1));
// 		$this->classLoader->add($namespace, $descriptor->getDirectory() . 'src');
		
		/* @var $mod ModuleInterface */
		$mod = new $className();
		$mod->setEntity($entity);
		$mod->setKeeko($this->keeko);
		$this->loadedModules[$packageName] = $mod;

		return $mod;
	}
	
	public function activate($packageName) {
		if (array_key_exists($packageName, $this->installedModules) 
				&& !array_key_exists($packageName, $this->activatedModules)) {
			
			$module = ModuleQuery::create()->findOneByName($packageName);
			$package = $this->packageManager->getModulePackage($packageName);
			
			// install actions
			$extra = $package->getExtra();
			$this->installActions($module, $extra['module']);
		}
	}
	
	private function installActions(Module $module, $data) {
		if (!array_key_exists('actions', $data)) {
			return;
		}
		
		foreach ($data['actions'] as $name => $options) {
			$a = new Action();
			$a->setName($name);
			$a->setModule($module);

			if (array_key_exists('title', $options)) {
				$a->setTitle($options['title']);
			}

			if (array_key_exists('description', $options)) {
				$a->setDescription($options['description']);
			}

			if (array_key_exists('api', $options)) {
				$a->setApi($options['api']);
			}

			$a->save();
		}
	}
	
	public function deactivate($packageName) {
		if (array_key_exists($packageName, $this->activatedModules)
				&& !array_key_exists($packageName, $this->installedModules)) {
			ModuleQuery::create()->filterByName($packageName)->delete();
		}
	}

// 	/**
// 	 * Returns wether a module was loaded
// 	 *
// 	 * @param String $packageName
// 	 * @return boolean true if loaded, false if not
// 	 */
// 	public function isLoaded($packageName) {
// 		return array_key_exists($packageName, $this->loadedModules);
// 	}
}
