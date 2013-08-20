<?php
namespace keeko\core\installer;

use keeko\core\entities\Application;
use Composer\Package\CompletePackageInterface;
use Composer\IO\IOInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use keeko\core\entities\RouterQuery;
use keeko\core\entities\DesignQuery;
use keeko\core\entities\ApplicationUri;
use keeko\core\entities\ApplicationQuery;

class AppInstaller implements KeekoPackageInstallerInterface {
	
	public function install(IOInterface $io, CompletePackageInterface $package) {
		$extra = $package->getExtra();
		
		$io->write('[Keeko] Install Application: ' . $package->getName());
		
		if (array_key_exists('keeko', $extra) && array_key_exists('app', $extra['keeko'])) {
			$params = $extra['keeko']['app'];
			
			// options
			$resolver = new OptionsResolver();
			$resolver->setRequired(['router', 'design', 'title']);
			$options = $resolver->resolve($params);
			
			// find router
			$router = RouterQuery::create()->findOneByName($options['router']);
			
			// find design
			$design = DesignQuery::create()->findOnByName($options['design']);
			
			$app = new Application();
			$app->setTitle($options['title']);
			$app->setName($package->getName());
			$app->setDescription($package->getDescription());
			$app->setInstalledVersion($package->getPrettyVersion());
			$app->setRouter($router);
			$app->setDesign($design);
			$app->save();
			
			// TODO: Somehow get an Application Uri
// 			$uri = new ApplicationUri();
// 			$uri->set			
		}
	}
	
	public function update(IOInterface $io, CompletePackageInterface $initial, CompletePackageInterface $target) {
		// nothing to do here
	}
	
	public function uninstall(IOInterface $io, CompletePackageInterface $package) {
		// nothing to do here
	}
}