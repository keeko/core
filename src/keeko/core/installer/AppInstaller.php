<?php
namespace keeko\core\installer;

use keeko\core\entities\Application;
use Composer\Package\CompletePackageInterface;
use Composer\IO\IOInterface;

class AppInstaller implements KeekoPackageInstallerInterface {
	
	public function install(IOInterface $io, CompletePackageInterface $package) {
		$extra = $package->getExtra();
		
		$io->write('Install Application: ' . $package->getName());
		
		if (array_key_exists('keeko', $extra) && array_key_exists('app-class', $extra['keeko'])) {
			$appClass = $extra['keeko']['app-class'];
			
			
			
			$io->write('app-class found.');
			
			$app = new ApplicationType();
			$app->setTitle($package->getName());
			$app->setDescription($package->getDescription());
			$app->setInstalledVersion($package->getPrettyVersion());
			$app->setClassname($appClass);
			$app->save();
			
			
// 			$app = new $appClass();
// 			$app->install();
			
			
		}
	}
	
	public function update(IOInterface $io, CompletePackageInterface $initial, CompletePackageInterface $target) {
		$extra = $target->getExtra();
		
		if (array_key_exists('keeko', $extra) && array_key_exists('app-class', $extra['keeko'])) {
			$appClass = $extra['keeko']['app-class'];
			$app = new $appClass();
			$app->update($initial, $target);
		}
	}
	
	public function uninstall(IOInterface $io, CompletePackageInterface $package) {
		$extra = $package->getExtra();
		
		$io->write('Uninstall Application: ' . $package->getName());
		
		if (array_key_exists('keeko', $extra) && array_key_exists('app-class', $extra['keeko'])) {
			$appClass = $extra['keeko']['app-class'];
			
			$io->write('app-class found.');
			
// 			$app = new $appClass();
// 			$app->uninstall();
		}
	}
}