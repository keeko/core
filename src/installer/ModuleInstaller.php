<?php
namespace keeko\core\installer;

use Composer\IO\IOInterface;
use Composer\Package\CompletePackageInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use keeko\core\model\Module;
use keeko\core\model\ModuleQuery;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use keeko\core\service\ServiceContainer;

class ModuleInstaller extends AbstractPackageInstaller {

	public function install(IOInterface $io, CompletePackageInterface $package) {
		$io->write('[Keeko] Install Module: ' . $package->getName());
		
		$extra = $package->getExtra();
		
		if (array_key_exists('keeko', $extra) && array_key_exists('module', $extra['keeko'])) {
			$params = $extra['keeko']['module'];

			// options
			$resolver = new OptionsResolver();
			$resolver->setRequired(['class', 'title', 'slug']);
			$resolver->setOptional(['actions', 'api', 'default-action', 'codegen']);
			$options = $resolver->resolve($params);

			// create module
			$className = $options['class'][0] == '\\' ? $options['class'] : '\\' . $options['class'];
			$module = new Module();
			$module->setClassName($className);
			$module->setTitle($options['title']);
			$module->setSlug($options['slug']);
			$module->setDefaultAction($options['default-action']);
			$this->updatePackage($module, $package);
			
			// run module -> install
			$class = new $className($module, new ServiceContainer());
			$class->install();
		}
	}

	public function update(IOInterface $io, CompletePackageInterface $initial, CompletePackageInterface $target) {
		// retrieve module
		$module = ModuleQuery::create()->findOneByName($target->getName());
		
		// update if activated
		if ($module !== null) {
			
			if ($module->getActivatedVersion() !== null) {
				// call something like $module->update($initial->getPrettyVersion(), $target->getPrettyVersion());
				$io->write(sprintf('[Keeko] Update Module: %s from %s to %s', $target->getName(), $initial->getPrettyVersion(), $target->getPrettyVersion()));
				
				// TODO: Run ModuleManager->update($name)
			}
			
			$this->updatePackage($module, $target);
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