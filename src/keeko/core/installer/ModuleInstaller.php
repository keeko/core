<?php
namespace keeko\core\installer;

use Composer\Package\PackageInterface;
use keeko\core\entities\Module;
use keeko\core\entities\ModuleQuery;

class ModuleInstaller extends AbstractPackageInstaller {
	
	public function install(IOInterface $io, CompletePackageInterface $package) {
		$extra = $package->getExtra();
		
		$io->write('[Keeko] Install Module: ' . $package->getName());		

		if (array_key_exists('keeko', $extra) && array_key_exists('app', $extra['keeko'])) {
			$params = $extra['keeko']['module'];
				
			// options
			$resolver = new OptionsResolver();
			$resolver->setRequired(['class', 'title', 'defaultAction']);
			$options = $resolver->resolve($params);
				
			$module = new Module();
			$module->setClassName($options['class']);
			$module->setTitle($options['title']);
			$module->setDefaultAction($options['defaultAction']);
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
		}
	}
	
	public function uninstall(IOInterface $io, CompletePackageInterface $package) {
		// retrieve module
		$module = ModuleQuery::create()->findOneByName($target->getName());

		$io->write('[Keeko] Uninstall Module: ' . $package->getName());

		// delete if found
		if ($module !== null) {
			$module->delete();
		}
	}
}