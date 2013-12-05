<?php
namespace keeko\core\installer;

use keeko\core\entities\Module;
use keeko\core\entities\ModuleQuery;
use Composer\IO\IOInterface;
use Composer\Package\CompletePackageInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModuleInstaller extends AbstractPackageInstaller {
	
	public function install(IOInterface $io, CompletePackageInterface $package) {
		$io->write('[Keeko] Install Module: ' . $package->getName());
		
		$extra = $package->getExtra();
		
		if (array_key_exists('keeko', $extra) && array_key_exists('module', $extra['keeko'])) {
			$params = $extra['keeko']['module'];
				
			// check params
			$missing = [];
			$required = ['class', 'title', 'default-action'];
			foreach ($required as $key) {
				if (!array_key_exists($key, $params)) {
					$missing[] = $key;
				}
			}
			
			if (count($missing)) {
				throw new \Exception('Missing parameters for module: ' . implode(', ', $missing));
			}

			// create entity
			$module = new Module();
			$module->setClassName($params['class']);
			$module->setTitle($params['title']);
			$module->setDefaultAction($params['default-action']);
			$this->updatePackage($module, $package);
		}
	}
	
	public function update(IOInterface $io, CompletePackageInterface $initial, CompletePackageInterface $target) {
		// retrieve module
		$module = ModuleQuery::create()->findOneByName($target->getName());

		// update if activated
		if ($module !== null && $module->getActivatedVersion() !== null) {
			
			// call something like $module->update($initial->getPrettyVersion(), $target->getPrettyVersion());
			$io->write(sprintf('[Keeko] Update Module: %s from %s to %s', 
				$package->getName(), $initial->getPrettyVersion(), $target->getPrettyVersion()));
			
			// TODO: Run ModuleManager->update($name)
		}
	}
	
	public function uninstall(IOInterface $io, CompletePackageInterface $package) {
		$io->write('[Keeko] Uninstall Module: ' . $package->getName());
		
		// retrieve module
		$module = ModuleQuery::create()->findOneByName($package->getName());
		
		// delete if found
		if ($module !== null) {
			$module->delete();
		}
	}
}