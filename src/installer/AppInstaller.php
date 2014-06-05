<?php
namespace keeko\core\installer;

use Composer\Package\CompletePackageInterface;
use Composer\IO\IOInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use keeko\core\model\Application;
use keeko\core\model\ApplicationQuery;

class AppInstaller extends AbstractPackageInstaller {

	public function install(IOInterface $io, CompletePackageInterface $package) {
		$app = ApplicationQuery::create()->findOneByName($package->getName());
		
		if ($app === null) {
			$io->write('[Keeko] Install Application: ' . $package->getName());
			
			$extra = $package->getExtra();
			if (isset($extra['keeko']) && isset($extra['keeko']['app'])) {
				$params = $extra['keeko']['app'];
				
				// options
				$resolver = new OptionsResolver();
				$resolver->setRequired([
						'class',
						'title'
				]);
				$options = $resolver->resolve($params);
				
				$app = new Application();
				$app->setTitle($options['title']);
				$app->setClassName($options['class']);
				$app->setName($package->getName());
				
				$this->updatePackage($app, $package);
			}
		}
		
		return $app;
	}

	public function update(IOInterface $io, CompletePackageInterface $initial, CompletePackageInterface $target) {
		// nothing to do here
	}

	public function uninstall(IOInterface $io, CompletePackageInterface $package) {
		// nothing to do here
	}
}